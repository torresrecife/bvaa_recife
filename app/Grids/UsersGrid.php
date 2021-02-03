<?php

namespace App\Grids;

use App\Role;
use Closure;
use Leantony\Grid\Grid;

class UsersGrid extends Grid implements UsersGridInterface
{
    /**
     * The name of the grid
     *
     * @var string
     */
    protected $name = 'Users';

    /**
     * List of buttons to be generated on the grid
     *
     * @var array
     */
    protected $buttonsToGenerate = [
//        'create',
//        'view',
//        'delete',
//        'refresh',
//        'export'
    ];

    /**
     * Specify if the rows on the table should be clicked to navigate to the record
     *
     * @var bool
     */
    protected $linkableRows = false;

    /**
    * Set the columns to be displayed.
    *
    * @return void
    * @throws \Exception if an error occurs during parsing of the data
    */
    public function setColumns()
    {
        $this->columns = [
            "CodigoProcesso" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "styles" => ["column" => "success"],
                "link_basic"  => ["neo" => "http://192.168.81.200/Modulos/ElementosProcessuais/ProcessoFichaGeral.aspx?idProcesso="]
            ],
            "Area" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "link_basic"  => ["neo" => ""]
            ],
            "TipoProcesso" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "link_basic"  => ["neo" => ""]
            ],
            "NumeroProcessoCNJ" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "link_basic"  => ["neo" => ""]
            ],
            "Comarca" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "link_basic"  => ["neo" => ""]
            ],
            "FaseProcesso" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "link_basic"  => ["neo" => ""]
            ],
            "Carteira" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "link_basic"  => ["neo" => ""]
            ],
            "StatusProcesso" => [
                "search" => ["enabled" => true],
                "filter" => ["enabled" => true, "operator" => "="],
                "link_basic"  => ["neo" => ""]
            ],
            "DataAjuizamento" => [
                "sort" => false, "date" => true,
                "filter" => ["enabled" => true, "type" => "daterange"]
            ],
//            "role_id" => [
//                'label' => 'Role',
//                'export' => true,
//                'search' => ['enabled' => false],
//                'presenter' => function ($columnData, $columnName) {
//                    return $columnData->role->name;
//                },
//                'filter' => [
//                    'enabled' => true,
//                    'type' => 'select',
//                    'data' => Role::query()->pluck('name', 'id')
//                ]
//            ],
        ];
    }

    /**
     * Set the links/routes. This are referenced using named routes, for the sake of simplicity
     *
     * @return void
     */
    public function setRoutes()
    {
        // searching, sorting and filtering
        $this->setIndexRouteName('users.index');

        // crud support
        $this->setCreateRouteName('users.create');
        $this->setViewRouteName('users.show');
        $this->setDeleteRouteName('users.destroy');

        // default route parameter
        $this->setDefaultRouteParameter('id');
    }

    /**
    * Return a closure that is executed per row, to render a link that will be clicked on to execute an action
    *
    * @return Closure
    */
    public function getLinkableCallback(): Closure
    {
        return function ($gridName, $item) {
            return route($this->getViewRouteName(), [$gridName => $item->id]);
        };
    }

    /**
    * Configure rendered buttons, or add your own
    *
    * @return void
    */
    public function configureButtons()
    {
        // call `addRowButton` to add a row button
        // call `addToolbarButton` to add a toolbar button
        // call `makeCustomButton` to do either of the above, but passing in the button properties as an array

        // call `editToolbarButton` to edit a toolbar button
        // call `editRowButton` to edit a row button
        // call `editButtonProperties` to do either of the above. All the edit functions accept the properties as an array
    }

    /**
    * Returns a closure that will be executed to apply a class for each row on the grid
    * The closure takes two arguments - `name` of grid, and `item` being iterated upon
    *
    * @return Closure
    */
    public function getRowCssStyle(): Closure
    {
        return function ($gridName, $item) {
            // e.g, to add a success class to specific table rows;
            // return $item->id % 2 === 0 ? 'table-success' : '';
            //return $item->id % 3 === 0 ? 'table-success' : '';
            return true; //$item->id % 3 === 0 ? 'table-success' : '';
        };
    }
}