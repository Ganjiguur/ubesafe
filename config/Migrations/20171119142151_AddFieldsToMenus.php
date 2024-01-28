<?php
use Migrations\AbstractMigration;

class AddFieldsToMenus extends AbstractMigration
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
        $table = $this->table('menus');
        $table->addColumn('show_on_footer', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->addColumn('show_on_mobile', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->addColumn('show_on_mainmenu', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }
}
