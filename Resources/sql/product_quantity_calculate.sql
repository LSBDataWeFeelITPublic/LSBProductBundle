DROP FUNCTION IF EXISTS product_quantity_calculate(integer);

CREATE OR REPLACE FUNCTION product_quantity_calculate(param_product_id integer)
    RETURNS boolean AS
$BODY$

DECLARE
    LOCAL_STORAGE_TYPE    constant            INT     := 10;
    EXTERNAL_STORAGE_TYPE constant            INT     := 20;
    fetched_product_set_id                    INT;
    is_available_for_order                    BOOLEAN := TRUE;
    product_record                            record;
    local_quantity_value                      INT;
    external_quantity_value                   INT;
    local_quantity_available_at_hand_value    INT;
    external_quantity_available_at_hand_value INT;
    is_product_set_value                      BOOLEAN := FALSE;
    local_quantity_record                     record;
    external_quantity_record                  record;
BEGIN
    IF (SELECT COUNT(id) FROM product_app_product WHERE id = param_product_id) = 1 THEN
        SELECT p.*
        INTO product_record
        FROM product_app_product p
        WHERE p.id = param_product_id
        LIMIT 1;

        is_product_set_value := product_record.is_product_set;

        if (is_product_set_value) THEN
            PERFORM calculate_product_set_quantity(param_product_id);
            RETURN FALSE;
        end if;

        IF (product_record IS NULL
            OR product_record.is_enabled = FALSE
            OR product_record.is_enabled IS NULL
            ) THEN
            is_available_for_order := FALSE;
        END IF;

        SELECT COALESCE(SUM(pq.quantity), 0) as quantity, COALESCE(SUM(pq.quantity_available_at_hand), 0) as quantity_available_at_hand
        INTO local_quantity_record
        FROM product_app_product_quantity pq
        WHERE pq.product_id = param_product_id
          AND pq.storage_id IN (SELECT s.id FROM product_app_storage s WHERE s.type = LOCAL_STORAGE_TYPE);

        SELECT COALESCE(SUM(pq.quantity), 0) as quantity, COALESCE(SUM(pq.quantity_available_at_hand), 0) as quantity_available_at_hand
        INTO external_quantity_record
        FROM product_app_product_quantity pq
        WHERE pq.product_id = param_product_id
          AND pq.storage_id IN (SELECT s.id FROM product_app_storage s WHERE s.type = EXTERNAL_STORAGE_TYPE);

        local_quantity_value := local_quantity_record.quantity;
        local_quantity_available_at_hand_value := local_quantity_record.quantity_available_at_hand;
        external_quantity_value := external_quantity_record.quantity;
        external_quantity_available_at_hand_value := external_quantity_record.quantity_available_at_hand;

        IF NOT is_available_for_order
        THEN
            local_quantity_value := 0;
            external_quantity_value := 0;
            local_quantity_available_at_hand_value := 0;
            external_quantity_available_at_hand_value := 0;
        END IF;

        UPDATE product_app_product
        SET local_quantity                      = local_quantity_value,
            external_quantity                   = external_quantity_value,
            quantity                            = local_quantity_value + external_quantity_value,
            quantity_available_at_hand          = local_quantity_available_at_hand_value + external_quantity_available_at_hand_value,
            local_quantity_available_at_hand    = local_quantity_available_at_hand_value,
            external_quantity_available_at_hand = external_quantity_available_at_hand_value
        WHERE id = param_product_id
          AND is_product_set = FALSE;

--      We try to calculate the value of the set if the product is a component of a set
        FOR fetched_product_set_id IN (SELECT DISTINCT psp.product_set_id FROM product_app_product_set_product psp WHERE psp.product_id = param_product_id)
            LOOP
                PERFORM calculate_product_set_quantity(fetched_product_set_id);
            end loop;

        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;

END

$BODY$
    LANGUAGE plpgsql VOLATILE
                     COST 100;