DROP FUNCTION IF EXISTS calculate_product_set_quantity(integer);

CREATE OR REPLACE FUNCTION calculate_product_set_quantity(param_product_set_id integer)
    RETURNS boolean AS
$BODY$

DECLARE
    calculated_available_at_hand_from_local_storages         INT     := null;
    calculated_available_at_hand_from_external_storages      INT     := null;
    calculated_quantity_available_at_hand                    INT     := null;
    calculated_quantity                                      INT     := null;
    calculated_local_quantity                                INT     := null;
    calculated_external_quantity                             INT     := null;
    temp_shop_calculated_availability_from_local_storages    INT     := null;
    temp_shop_calculated_availability_from_external_storages INT     := null;
    temp_quantity_available_at_hand                          INT     := null;
    temp_quantity                                            INT     := null;
    temp_local_quantity                                      INT     := null;
    temp_external_quantity                                   INT     := null;
    product_set_product_row                                  record;
    set_product_row                                          record;
    is_available_for_order                                   BOOLEAN := TRUE;
BEGIN
    IF (SELECT COUNT(id) FROM product_app_product WHERE id = param_product_set_id AND is_product_set = TRUE) > 0 THEN
        SELECT p.*
        INTO product_set_product_row
        FROM product_app_product p
        WHERE p.id = param_product_set_id
        LIMIT 1;

        IF product_set_product_row IS NULL
            OR product_set_product_row.is_enabled = FALSE
            OR product_set_product_row.is_enabled IS NULL
        THEN
            is_available_for_order := FALSE;
        END IF;

        FOR product_set_product_row IN (SELECT psp.* FROM product_app_product_set_product psp WHERE psp.product_set_id = param_product_set_id ORDER BY id ASC LIMIT 10000)
            LOOP
                SELECT * INTO set_product_row FROM product_app_product WHERE id = product_set_product_row.product_id LIMIT 1;

                if (set_product_row.quantity_available_at_hand > 0) THEN
                    temp_quantity_available_at_hand := floor(set_product_row.quantity_available_at_hand / product_set_product_row.quantity);
                else
                    temp_quantity_available_at_hand := 0;
                end if;

                if (set_product_row.quantity > 0) THEN
                    temp_quantity := floor(set_product_row.quantity / product_set_product_row.quantity);
                else
                    temp_quantity := 0;
                end if;

                if (set_product_row.local_quantity > 0) THEN
                    temp_local_quantity := floor(set_product_row.local_quantity / product_set_product_row.quantity);
                else
                    temp_local_quantity := 0;
                end if;

                if (set_product_row.external_quantity > 0) THEN
                    temp_external_quantity := floor(set_product_row.external_quantity / product_set_product_row.quantity);
                else
                    temp_external_quantity := 0;
                end if;

                if (set_product_row.local_quantity_available_at_hand > 0) THEN
                    temp_shop_calculated_availability_from_local_storages := floor(set_product_row.local_quantity_available_at_hand / product_set_product_row.quantity);
                else
                    temp_shop_calculated_availability_from_local_storages := 0;
                end if;

                if (set_product_row.external_quantity_available_at_hand > 0) THEN
                    temp_shop_calculated_availability_from_external_storages := floor(set_product_row.external_quantity_available_at_hand / product_set_product_row.quantity);
                else
                    temp_shop_calculated_availability_from_external_storages := 0;
                end if;

                if (calculated_quantity_available_at_hand IS NULL
                    OR calculated_quantity_available_at_hand IS NOT NULL AND temp_quantity_available_at_hand < calculated_quantity_available_at_hand
                    ) THEN
                    calculated_quantity_available_at_hand := temp_quantity_available_at_hand;
                end if;

                if (calculated_quantity IS NULL
                    OR calculated_quantity IS NOT NULL AND temp_quantity < calculated_quantity
                    ) THEN
                    calculated_quantity := temp_quantity;
                end if;

                if (calculated_local_quantity IS NULL
                    OR calculated_local_quantity IS NOT NULL AND temp_local_quantity < calculated_local_quantity
                    ) THEN
                    calculated_local_quantity := temp_local_quantity;
                end if;

                if (calculated_external_quantity IS NULL
                    OR calculated_external_quantity IS NOT NULL AND temp_external_quantity < calculated_external_quantity
                    ) THEN
                    calculated_external_quantity := temp_external_quantity;
                end if;

                if (calculated_available_at_hand_from_local_storages IS NULL OR
                    calculated_available_at_hand_from_local_storages IS NOT NULL AND temp_shop_calculated_availability_from_local_storages < calculated_available_at_hand_from_local_storages) THEN
                    calculated_available_at_hand_from_local_storages := temp_shop_calculated_availability_from_local_storages;
                end if;

                if (calculated_available_at_hand_from_external_storages IS NULL OR
                    calculated_available_at_hand_from_external_storages IS NOT NULL AND temp_shop_calculated_availability_from_external_storages < calculated_available_at_hand_from_external_storages) THEN
                    calculated_available_at_hand_from_external_storages := temp_shop_calculated_availability_from_external_storages;
                end if;
            END LOOP;

        IF (calculated_quantity IS NULL) THEN
            calculated_quantity := 0;
        END IF;

        IF (calculated_local_quantity IS NULL) THEN
            calculated_local_quantity := 0;
        END IF;

        IF (calculated_external_quantity IS NULL) THEN
            calculated_external_quantity := 0;
        END IF;

        IF (calculated_quantity_available_at_hand IS NULL) THEN
            calculated_quantity_available_at_hand := 0;
        END IF;

        IF (calculated_available_at_hand_from_external_storages IS NULL) THEN
            calculated_available_at_hand_from_external_storages := 0;
        END IF;

        IF (calculated_available_at_hand_from_local_storages IS NULL) THEN
            calculated_available_at_hand_from_local_storages := 0;
        END IF;

--      We check the condition of clearing the producy quantities
        IF NOT is_available_for_order THEN
            calculated_quantity := 0;
            calculated_local_quantity := 0;
            calculated_external_quantity := 0;
            calculated_quantity_available_at_hand := 0;
            calculated_available_at_hand_from_local_storages := 0;
            calculated_available_at_hand_from_external_storages := 0;
        END IF;

        UPDATE
            product_app_product
        SET quantity                            = calculated_quantity,
            local_quantity                      = calculated_local_quantity,
            external_quantity                   = calculated_external_quantity,
            quantity_available_at_hand          = calculated_quantity_available_at_hand,
            external_quantity_available_at_hand = calculated_available_at_hand_from_external_storages,
            local_quantity_available_at_hand    = calculated_available_at_hand_from_local_storages
        WHERE id = param_product_set_id
          AND is_product_set = TRUE;
        RETURN TRUE;
    END IF;

    RETURN FALSE;
END

$BODY$
    LANGUAGE plpgsql VOLATILE
                     COST 100;