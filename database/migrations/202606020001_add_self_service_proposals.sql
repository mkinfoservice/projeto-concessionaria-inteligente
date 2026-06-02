-- Adds public self-service proposal metadata.
-- Safe to run after the original schema has already been imported.

ALTER TABLE `propostas`
    MODIFY `vendedor_id` INT UNSIGNED DEFAULT NULL;

ALTER TABLE `propostas`
    ADD COLUMN `origem` ENUM('VENDEDOR','AUTOSSERVICO') NOT NULL DEFAULT 'VENDEDOR' AFTER `valor_total`,
    ADD COLUMN `indicado_por_vendedor_id` INT UNSIGNED DEFAULT NULL AFTER `vendedor_id`;

ALTER TABLE `propostas`
    ADD INDEX `idx_origem` (`origem`),
    ADD INDEX `idx_indicado_por_vendedor` (`indicado_por_vendedor_id`);

ALTER TABLE `propostas`
    ADD CONSTRAINT `fk_proposta_indicado_vendedor`
    FOREIGN KEY (`indicado_por_vendedor_id`) REFERENCES `vendedores` (`id`)
    ON DELETE SET NULL;
