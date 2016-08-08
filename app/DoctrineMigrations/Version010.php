<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version010 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->createExtTranslationsTable($schema);
        $this->createExtLogEntriesTable($schema);
        $this->createRolesTable($schema);
        $this->createUsersTable($schema);
        $this->createAppConfigTable($schema);
        $this->createClientsTable($schema);
        $this->createContactsTable($schema);
        $this->createContactDetailsTable($schema);
        $this->createContactTypesTable($schema);
        $this->createQuoteItemsTable($schema);
        $this->createQuotesTable($schema);



        //$this->addSql("CREATE TABLE acl_classes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, class_type VARCHAR(200) NOT NULL, UNIQUE INDEX UNIQ_69DD750638A36066 (class_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        //$this->addSql("CREATE TABLE acl_security_identities (id INT UNSIGNED AUTO_INCREMENT NOT NULL, identifier VARCHAR(200) NOT NULL, username TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8835EE78772E836AF85E0677 (identifier, username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        //$this->addSql("CREATE TABLE acl_object_identities (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_object_identity_id INT UNSIGNED DEFAULT NULL, class_id INT UNSIGNED NOT NULL, object_identifier VARCHAR(100) NOT NULL, entries_inheriting TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9407E5494B12AD6EA000B10 (object_identifier, class_id), INDEX IDX_9407E54977FA751A (parent_object_identity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        //$this->addSql("CREATE TABLE acl_object_identity_ancestors (object_identity_id INT UNSIGNED NOT NULL, ancestor_id INT UNSIGNED NOT NULL, INDEX IDX_825DE2993D9AB4A6 (object_identity_id), INDEX IDX_825DE299C671CEA1 (ancestor_id), PRIMARY KEY(object_identity_id, ancestor_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        //$this->addSql("CREATE TABLE acl_entries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, class_id INT UNSIGNED NOT NULL, object_identity_id INT UNSIGNED DEFAULT NULL, security_identity_id INT UNSIGNED NOT NULL, field_name VARCHAR(50) DEFAULT NULL, ace_order SMALLINT UNSIGNED NOT NULL, mask INT NOT NULL, granting TINYINT(1) NOT NULL, granting_strategy VARCHAR(30) NOT NULL, audit_success TINYINT(1) NOT NULL, audit_failure TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4 (class_id, object_identity_id, field_name, ace_order), INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9 (class_id, object_identity_id, security_identity_id), INDEX IDX_46C8B806EA000B10 (class_id), INDEX IDX_46C8B8063D9AB4A6 (object_identity_id), INDEX IDX_46C8B806DF9183C9 (security_identity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");

        //$this->addSql("ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        //$this->addSql("ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE");

        $this->addSql("ALTER TABLE contacts ADD CONSTRAINT FK_3340157319EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)");
        $this->addSql("ALTER TABLE contact_details ADD CONSTRAINT FK_E8092A0BE7A1254A FOREIGN KEY (contact_id) REFERENCES contacts (id)");
        $this->addSql("ALTER TABLE contact_details ADD CONSTRAINT FK_E8092A0B5F63AD12 FOREIGN KEY (contact_type_id) REFERENCES contact_types (id)");
        $this->addSql("ALTER TABLE quote_items ADD CONSTRAINT FK_ECE1642CDB805178 FOREIGN KEY (quote_id) REFERENCES quotes (id)");
        $this->addSql("ALTER TABLE quotes ADD CONSTRAINT FK_A1B588C519EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)");

        //$this->addSql("ALTER TABLE acl_object_identities ADD CONSTRAINT FK_9407E54977FA751A FOREIGN KEY (parent_object_identity_id) REFERENCES acl_object_identities (id)");
        //$this->addSql("ALTER TABLE acl_object_identity_ancestors ADD CONSTRAINT FK_825DE2993D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");
        //$this->addSql("ALTER TABLE acl_object_identity_ancestors ADD CONSTRAINT FK_825DE299C671CEA1 FOREIGN KEY (ancestor_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");
        //$this->addSql("ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B806EA000B10 FOREIGN KEY (class_id) REFERENCES acl_classes (id) ON UPDATE CASCADE ON DELETE CASCADE");
        //$this->addSql("ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B8063D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");
        //$this->addSql("ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B806DF9183C9 FOREIGN KEY (security_identity_id) REFERENCES acl_security_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");

        $this->addSql("CREATE TABLE config_sections (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(125) NOT NULL, UNIQUE INDEX UNIQ_965EAD465E237E06 (name), INDEX IDX_965EAD46727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE config_sections ADD CONSTRAINT FK_965EAD46727ACA70 FOREIGN KEY (parent_id) REFERENCES config_sections (id)");
        $this->addSql("ALTER TABLE app_config ADD section_id INT DEFAULT NULL, DROP section");
        $this->addSql("ALTER TABLE app_config ADD CONSTRAINT FK_318942FCD823E37A FOREIGN KEY (section_id) REFERENCES config_sections (id)");
        $this->addSql("CREATE INDEX IDX_318942FCD823E37A ON app_config (section_id)");

        $this->addSql("CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, client_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION NOT NULL, due DATE DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, users LONGTEXT NOT NULL COMMENT '(DC2Type:array)', INDEX IDX_6A2F2F956BF700BD (status_id), INDEX IDX_6A2F2F9519EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE invoice_items (id INT AUTO_INCREMENT NOT NULL, invoice_id INT DEFAULT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, qty DOUBLE PRECISION NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, INDEX IDX_DCC4B9F82989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F9519EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)");
        $this->addSql("ALTER TABLE invoice_items ADD CONSTRAINT FK_DCC4B9F82989F1FD FOREIGN KEY (invoice_id) REFERENCES invoices (id)");

        $this->addSql("ALTER TABLE contact_types ADD required TINYINT(1) NOT NULL");

        $this->addSql("ALTER TABLE quotes CHANGE discount discount DOUBLE PRECISION DEFAULT NULL");
        $this->addSql("ALTER TABLE invoices CHANGE discount discount DOUBLE PRECISION DEFAULT NULL");

        $this->addSql("ALTER TABLE app_config ADD field_type VARCHAR(255) DEFAULT NULL, ADD field_options LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)'");

        $this->addSql("ALTER TABLE quotes ADD base_total DOUBLE PRECISION NOT NULL");
        $this->addSql("ALTER TABLE invoices ADD base_total DOUBLE PRECISION NOT NULL");

        $this->addSql("ALTER TABLE invoices ADD paid_date DATETIME NULL");

        $this->addSql("CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(125) NOT NULL, `label` VARCHAR(125) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, entity VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE clients ADD CONSTRAINT FK_C82E746BF700BD FOREIGN KEY (status_id) REFERENCES status (id)");
        $this->addSql("ALTER TABLE quotes ADD CONSTRAINT FK_A1B588C56BF700BD FOREIGN KEY (status_id) REFERENCES status (id)");
        $this->addSql("ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F956BF700BD FOREIGN KEY (status_id) REFERENCES status (id)");

        $this->addSql("ALTER TABLE ext_log_entries CHANGE object_id object_id VARCHAR(64) DEFAULT NULL");
        $this->addSql("CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)");
        $this->addSql("ALTER TABLE quotes ADD uuid VARCHAR(36) NOT NULL COMMENT '(DC2Type:uuid)'");
        $this->addSql("ALTER TABLE invoices ADD uuid VARCHAR(36) NOT NULL COMMENT '(DC2Type:uuid)'");

        $this->addSql("ALTER TABLE contact_types ADD type VARCHAR(45) NOT NULL, ADD field_options LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)'");

        $this->addSql("CREATE TABLE version (version VARCHAR(125) NOT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");

        //$this->addSql("DROP TABLE user_role");
        $this->addSql("DROP INDEX UNIQ_1483A5E9F85E0677 ON users");
        $this->addSql("DROP INDEX UNIQ_1483A5E9E7927C74 ON users");
        $this->addSql("ALTER TABLE users ADD username_canonical VARCHAR(255) NOT NULL, ADD email_canonical VARCHAR(255) NOT NULL, ADD last_login DATETIME DEFAULT NULL, ADD locked TINYINT(1) NOT NULL, ADD expired TINYINT(1) NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(255) DEFAULT NULL, ADD password_requested_at DATETIME DEFAULT NULL, ADD roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', ADD credentials_expired TINYINT(1) NOT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE salt salt VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE active enabled TINYINT(1) NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_1483A5E992FC23A8 ON users (username_canonical)");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_1483A5E9A0D96FBF ON users (email_canonical)");
        $this->addSql("UPDATE users SET roles = 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', username_canonical = username, email_canonical = email, enabled = 1");

        $this->addSql("INSERT INTO `config_sections` VALUES (NULL, NULL, 'system')");
        $this->addSql("INSERT INTO `config_sections` VALUES (NULL, LAST_INSERT_ID(), 'general')");

        $this->addSql(
            "INSERT INTO `app_config` VALUES
          (NULL, 'app_name', 'CSBill', NULL, LAST_INSERT_ID(), NULL, 'a:0:{}'),
          (NULL, 'logo', NULL, NULL, LAST_INSERT_ID(), 'image_upload', 'a:0:{}')
        "
        );

        $this->addSql("INSERT INTO `config_sections` VALUES (NULL, NULL, 'quote')");
        $this->addSql(
            "INSERT INTO `app_config` VALUES
          (NULL, 'email_subject', 'New Quotation - #{id}', 'To include the id of the quote in the subject, add the placeholder {id} where you want the id', LAST_INSERT_ID(), NULL, 'a:0:{}')
        "
        );

        $this->addSql("INSERT INTO `config_sections` VALUES (NULL, NULL, 'invoice')");
        $this->addSql(
            "INSERT INTO `app_config` VALUES
          (NULL, 'email_subject', 'New Invoice - #{id}', 'To include the id of the invoice in the subject, add the placeholder {id} where you want the id', LAST_INSERT_ID(), NULL, 'a:0:{}')
        "
        );

        $this->addSql("INSERT INTO `config_sections` VALUES (NULL, NULL, 'email')");
        $this->addSql(
            "INSERT INTO `app_config` VALUES
          (NULL, 'from_name', 'CSBill', NULL, LAST_INSERT_ID(), NULL, 'a:0:{}'),
          (NULL, 'from_address', 'info@csbill.org', NULL, LAST_INSERT_ID(), NULL, 'a:0:{}'),
          (NULL, 'format', 'both', 'In what format should emails be sent.', LAST_INSERT_ID(), 'radio', 'a:3:{s:4:\"html\";s:4:\"html\";s:4:\"text\";s:4:\"text\";s:4:\"both\";s:4:\"both\";}')
        "
        );

        $this->addSql(
            "INSERT INTO `contact_types` VALUES
          (NULL, 'email', 1, 'email', 'a:1:{s:11:\"constraints\";a:1:{i:0;s:5:\"email\";}}'),
          (NULL, 'mobile', 0, 'text', 'N;'),
          (NULL, 'phone', 0, 'text', 'N;'),
          (NULL, 'address', 0, 'textarea', 'N;')
        "
        );
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC");
        $this->addSql("ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395");
        $this->addSql("ALTER TABLE contacts DROP FOREIGN KEY FK_3340157319EB6921");
        $this->addSql("ALTER TABLE quotes DROP FOREIGN KEY FK_A1B588C519EB6921");
        $this->addSql("ALTER TABLE contact_details DROP FOREIGN KEY FK_E8092A0BE7A1254A");
        $this->addSql("ALTER TABLE contact_details DROP FOREIGN KEY FK_E8092A0B5F63AD12");
        $this->addSql("ALTER TABLE quote_items DROP FOREIGN KEY FK_ECE1642CDB805178");
        $this->addSql("ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B806EA000B10");
        $this->addSql("ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B806DF9183C9");
        $this->addSql("ALTER TABLE acl_object_identities DROP FOREIGN KEY FK_9407E54977FA751A");
        $this->addSql("ALTER TABLE acl_object_identity_ancestors DROP FOREIGN KEY FK_825DE2993D9AB4A6");
        $this->addSql("ALTER TABLE acl_object_identity_ancestors DROP FOREIGN KEY FK_825DE299C671CEA1");
        $this->addSql("ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B8063D9AB4A6");
        $this->addSql("DROP TABLE ext_translations");
        $this->addSql("DROP TABLE ext_log_entries");
        $this->addSql("DROP TABLE roles");
        $this->addSql("DROP TABLE users");
        $this->addSql("DROP TABLE user_role");
        $this->addSql("DROP TABLE app_config");
        $this->addSql("DROP TABLE clients");
        $this->addSql("DROP TABLE contacts");
        $this->addSql("DROP TABLE contact_details");
        $this->addSql("DROP TABLE contact_types");
        $this->addSql("DROP TABLE quote_items");
        $this->addSql("DROP TABLE quotes");
        $this->addSql("DROP TABLE acl_classes");
        $this->addSql("DROP TABLE acl_security_identities");
        $this->addSql("DROP TABLE acl_object_identities");
        $this->addSql("DROP TABLE acl_object_identity_ancestors");
        $this->addSql("DROP TABLE acl_entries");

        $this->addSql("ALTER TABLE config_sections DROP FOREIGN KEY FK_965EAD46727ACA70");
        $this->addSql("ALTER TABLE app_config DROP FOREIGN KEY FK_318942FCD823E37A");
        $this->addSql("DROP TABLE config_sections");
        $this->addSql("DROP INDEX IDX_318942FCD823E37A ON app_config");
        $this->addSql("ALTER TABLE app_config ADD section VARCHAR(125) NOT NULL, DROP section_id");

        $this->addSql("ALTER TABLE invoice_items DROP FOREIGN KEY FK_DCC4B9F82989F1FD");
        $this->addSql("DROP TABLE invoices");
        $this->addSql("DROP TABLE invoice_items");

        $this->addSql("ALTER TABLE contact_types DROP required");

        $this->addSql("ALTER TABLE invoices CHANGE discount discount DOUBLE PRECISION NOT NULL");
        $this->addSql("ALTER TABLE quotes CHANGE discount discount DOUBLE PRECISION NOT NULL");

        $this->addSql("ALTER TABLE app_config DROP field_type, DROP field_options");

        $this->addSql("ALTER TABLE invoices DROP base_total");
        $this->addSql("ALTER TABLE quotes DROP base_total");

        $this->addSql("ALTER TABLE invoices DROP paid_date");

        $this->addSql("ALTER TABLE clients DROP FOREIGN KEY FK_C82E746BF700BD");
        $this->addSql("ALTER TABLE quotes DROP FOREIGN KEY FK_A1B588C56BF700BD");
        $this->addSql("ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F956BF700BD");
        $this->addSql("CREATE TABLE entity_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(125) NOT NULL, class VARCHAR(125) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, entity VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("DROP TABLE status");

        $this->addSql("DROP INDEX log_version_lookup_idx ON ext_log_entries");
        $this->addSql("ALTER TABLE ext_log_entries CHANGE object_id object_id VARCHAR(32) DEFAULT NULL");
        $this->addSql("ALTER TABLE invoices DROP uuid");
        $this->addSql("ALTER TABLE quotes DROP uuid");

        $this->addSql("ALTER TABLE contact_types DROP type, DROP field_options");

        $this->addSql("DROP TABLE version");

        $this->addSql("CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE");
        $this->addSql("DROP INDEX UNIQ_1483A5E992FC23A8 ON users");
        $this->addSql("DROP INDEX UNIQ_1483A5E9A0D96FBF ON users");
        $this->addSql("ALTER TABLE users ADD active TINYINT(1) NOT NULL, DROP username_canonical, DROP email_canonical, DROP enabled, DROP last_login, DROP locked, DROP expired, DROP expires_at, DROP confirmation_token, DROP password_requested_at, DROP roles, DROP credentials_expired, DROP credentials_expire_at, CHANGE username username VARCHAR(25) NOT NULL, CHANGE email email VARCHAR(60) NOT NULL, CHANGE salt salt VARCHAR(32) NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)");

        $this->addSql('DELETE FROM app_config WHERE `key` IN ("app_name", "logo", "email_subject", "from_name", "from_address", "format")');
        $this->addSql('DELETE FROM config_sections WHERE name IN ("general", "quote", "invoice")');
        $this->addSql('DELETE FROM config_sections WHERE name = "system"');
    }

    /**
     * @param Schema $schema
     */
    private function createExtTranslationsTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'ext_translations');

        $table->addColumn('locale', 'string', ['length' => 8]);
        $table->addColumn('object_class', 'string', ['length' => 255]);
        $table->addColumn('field', 'string', ['length' => 32]);
        $table->addColumn('foreign_key', 'string', ['length' => 64]);
        $table->addColumn('content', 'text', ['notnull' => false]);

        $table->addIndex(['locale', 'object_class', 'foreign_key'], 'translations_lookup_idx');

        $table->addUniqueIndex(['locale', 'object_class', 'field', 'foreign_key'], 'lookup_unique_idx');
    }

    private function createExtLogEntriesTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'ext_log_entries');

        $table->addColumn('action', 'string', ['length' => 8]);
        $table->addColumn('logged_at', 'datetimetz');
        $table->addColumn('object_id', 'string', ['length' => 32, 'default' => null, 'notnull' => false]);
        $table->addColumn('object_class', 'string', ['length' => 255]);
        $table->addColumn('version', 'integer');
        $table->addColumn('data', 'text', ['default' => null, 'notnull' => false, 'comment' => '(DC2Type:array)']);
        $table->addColumn('username', 'string', ['length' => 255, 'default' => null, 'notnull' => false]);

        $table->addIndex(['object_class'], 'log_class_lookup_idx');
        $table->addIndex(['logged_at'], 'log_date_lookup_idx');
        $table->addIndex(['username'], 'log_user_lookup_idx');
    }

    private function createRolesTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'roles');

        $table->addColumn('name', 'string', ['length' => 25]);
        $table->addColumn('role', 'string', ['length' => 25]);
        $table->addUniqueIndex(['name'], 'UNIQ_B63E2EC75E237E06');
        $table->addUniqueIndex(['role'], 'UNIQ_B63E2EC757698A6A');
    }

    private function createUsersTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'users');

        $table->addColumn('username', 'string', ['length' => 25]);
        $table->addColumn('salt', 'string', ['length' => 32]);
        $table->addColumn('password', 'string', ['length' => 255]);
        $table->addColumn('email', 'string', ['length' => 60]);
        $table->addColumn('active', 'boolean');

        $table->addUniqueIndex(['username'], 'UNIQ_1483A5E9F85E0677');
        $table->addUniqueIndex(['email'], 'UNIQ_1483A5E9E7927C74');
    }

    private function createAppConfigTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'app_config');

        $table->addColumn('`key`', 'string', ['length' => 255]);
        $table->addColumn('`value`', 'text');
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('section', 'string', ['length' => 125]);

        $table->addIndex(['`key`']);
        $table->addIndex(['section']);
        $table->addUniqueIndex(['`key`', 'section']);
    }

    private function createClientsTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'clients', true);

        $table->addColumn('status_id', 'integer', ['notnull' => false]);
        $table->addColumn('name', 'string', ['length' => 125]);
        $table->addColumn('website', 'string', ['length' => 125]);

        $table->addUniqueIndex(['name'], 'UNIQ_C82E745E237E06');
        $table->addIndex(['status_id'], 'IDX_C82E746BF700BD');
    }

    private function createContactsTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'contacts', true);

        $table->addColumn('client_id', 'integer');
        $table->addColumn('firstname', 'string', ['length' => 125]);
        $table->addColumn('lastname', 'string', ['length' => 125, 'notnull' => false]);

        $table->addIndex(['client_id'], 'IDX_3340157319EB6921');
    }

    private function createContactDetailsTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'contact_details');

        $table->addColumn('contact_id', 'integer');
        $table->addColumn('contact_type_id', 'integer');
        $table->addColumn('value', 'text');
        $table->addColumn('is_primary', 'boolean', ['default' => 0]);

        $table->addIndex(['contact_id'], 'IDX_E8092A0BE7A1254A');
        $table->addIndex(['contact_type_id'], 'IDX_E8092A0B5F63AD12');
    }

    private function createContactTypesTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'contact_types');

        $table->addColumn('name', 'string', ['length' => 45]);

        $table->addUniqueIndex(['name'], 'UNIQ_741A993F5E237E06');
    }

    private function createQuoteItemsTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'quote_items', true);

        $table->addColumn('quote_id', 'integer');
        $table->addColumn('description', 'text');
        $table->addColumn('price', 'decimal', ['precision' => 2]);
        $table->addColumn('qty', 'float');

        $table->addIndex(['quote_id'], 'IDX_ECE1642CDB805178');
    }

    private function createQuotesTable(Schema $schema)
    {
        $table = $this->getTable($schema, 'quotes', true);

        $table->addColumn('status_id', 'integer');
        $table->addColumn('client_id', 'integer');
        $table->addColumn('total', 'decimal');
        $table->addColumn('discount', 'decimal');
        $table->addColumn('due', 'datetimetz', ['notnull' => false]);
        $table->addColumn('users', 'text', ['comment' => '(DC2Type:array)']);

        $table->addIndex(['status_id'], 'IDX_A1B588C56BF700BD');
        $table->addIndex(['client_id'], 'IDX_A1B588C519EB6921');
    }

    /**
     * @param Schema $schema
     * @param string $name
     * @param bool   $includeTimestamps
     *
     * @return \Doctrine\DBAL\Schema\Table
     */
    private function getTable(Schema $schema, $name, $includeTimestamps = false)
    {
        $table = $schema->createTable($name);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);

        if (true === $includeTimestamps) {
            $table->addColumn('created', 'datetimetz');
            $table->addColumn('updated', 'datetimetz');
            $table->addColumn('deleted', 'datetimetz', ['notnull' => false]);
        }

        $table->setPrimaryKey(['id']);

        return $table;
    }
}
