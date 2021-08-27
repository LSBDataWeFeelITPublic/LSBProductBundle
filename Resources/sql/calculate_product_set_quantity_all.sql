DROP FUNCTION IF EXISTS calculate_product_set_quantity_all();

CREATE OR REPLACE FUNCTION calculate_product_set_quantity_all()
    RETURNS boolean AS
$BODY$

DECLARE
    fetched_product_set_id INT;

BEGIN
    FOR fetched_product_set_id IN (SELECT DISTINCT product_set_id FROM product_app_product_set_product)
        LOOP
            PERFORM calculate_product_set_quantity(fetched_product_set_id);
        end loop;

    RETURN TRUE;
END

$BODY$
    LANGUAGE plpgsql VOLATILE
                     COST 100;
