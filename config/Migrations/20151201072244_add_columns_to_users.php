<?php

use Migrations\AbstractMigration;

class AddColumnsToUsers extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('users');
        $table->addColumn('bio', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('last_login', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('photo', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('full_name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->addColumn('article_count', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('permission', 'text', [
            'default' => null,
            'null' => true,
        ]);

        $table->removeColumn('first_name');
        $table->removeColumn('last_name');
        $table->update();
    }

}
