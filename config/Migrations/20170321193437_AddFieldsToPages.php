<?php
use Migrations\AbstractMigration;

class AddFieldsToPages extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('pages');
        $table->addColumn('version_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('archived', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }
}
