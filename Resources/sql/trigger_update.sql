-- #1
DROP TRIGGER IF EXISTS qty_update on product_app_product_quantity;

CREATE OR REPLACE FUNCTION product_quantity_update()
    RETURNS trigger AS
$BODY$

DECLARE
    fetched_product_id INT := null;
BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        fetched_product_id = NEW.product_id;
    ELSEIF TG_OP = 'DELETE' THEN
        fetched_product_id = OLD.product_id;
    END IF;

    IF fetched_product_id IS NOT NULL THEN
        PERFORM product_quantity_calculate(fetched_product_id);
    END IF;

    RETURN NULL;
END

$BODY$
    LANGUAGE plpgsql VOLATILE
                     COST 100;

CREATE TRIGGER qty_update
    AFTER INSERT OR UPDATE OR DELETE
    ON product_app_product_quantity
    FOR EACH ROW
EXECUTE PROCEDURE product_quantity_update();

-- Product set recalculation after update of component products quantity
DROP TRIGGER IF EXISTS product_set_products_qty_update on product_app_product_set_product;

CREATE OR REPLACE FUNCTION product_set_products_quantity_update()
    RETURNS trigger AS
$BODY$

DECLARE
    fetched_product_set_id INT := null;
BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        fetched_product_set_id = NEW.product_set_id;
    ELSEIF TG_OP = 'DELETE' THEN
        fetched_product_set_id = OLD.product_set_id;
    END IF;

    IF fetched_product_set_id IS NOT NULL THEN
        PERFORM calculate_product_set_quantity(fetched_product_set_id);
    END IF;

    RETURN NULL;
END

$BODY$
    LANGUAGE plpgsql VOLATILE
                     COST 100;

CREATE TRIGGER product_set_products_qty_update
    AFTER INSERT OR UPDATE OR DELETE
    ON product_app_product_set_product
    FOR EACH ROW
EXECUTE PROCEDURE product_set_products_quantity_update();

-- Recalculation of quantity based on the isProductSet flag
DROP TRIGGER IF EXISTS product_type_qty_update on product_app_product;

CREATE OR REPLACE FUNCTION product_type_qty_update()
    RETURNS trigger AS
$BODY$

DECLARE
    product_id INT := null;
BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        product_id := NEW.id;

        IF product_id IS NOT NULL AND (TG_OP = 'UPDATE' AND (
            OLD.is_product_set <> NEW.is_product_set
            ) OR TG_OP = 'INSERT') THEN

            IF product_id IS NOT NULL AND (SELECT p.is_product_set FROM product_app_product p WHERE p.id = product_id LIMIT 1) THEN
                PERFORM calculate_product_set_quantity(product_id);
            ELSEIF product_id IS NOT NULL THEN
                PERFORM product_quantity_calculate(product_id);
            END IF;
        END IF;
    END IF;

    RETURN NULL;
END

$BODY$
    LANGUAGE plpgsql VOLATILE
                     COST 100;

CREATE TRIGGER product_type_qty_update
    AFTER INSERT OR UPDATE OR DELETE
    ON product_app_product
    FOR EACH ROW
EXECUTE PROCEDURE product_type_qty_update();