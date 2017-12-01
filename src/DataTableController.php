<?php

namespace Clover;

use Illuminate\Http\Request;

abstract class DataTableController extends Controller
{
    // test

    /**
     * The entity builder.
     *
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    protected $displayable_keys = ['attribute', 'text'];

    protected $displayable_values = [];

    protected $allow_creation = true;
    protected $allow_edition = true;
    protected $allow_deletion = true;

    protected $redirectable = true;

    /**
     * Get the builder for the entity.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    abstract public function builder();

    abstract protected function getRecords(); 

    function __construct()
    {
        $this->builder = $this->builder();
    }


    public function index(Request $request) 
    {
        $data = [
            'data' => [
                'table' => $this->getTableTitle(),
                'displayable' => $this->getDisplayableColumns(), 
                'records' => $this->getRecords(),
                'allow' => [
                    'creation' => $this->allow_creation,
                    'edition' => $this->allow_edition,
                    'deletion' => $this->allow_deletion,
                ],
                'redirect' => [
                    'enable' => $this->redirectable,
                    'create' => $this->getCreateRedirect(),
                    'edit' => $this->getEditRedirect()
                ]
            ]
        ];

        

        return response()->json($data);
    }

    protected function getTable() 
    {
        return $this->builder->getModel()->getTable();
    }

    protected function getTableTitle() 
    {
        return title_case($this->getTable());
    }

    protected function getDisplayableColumns()
    {
        return CloverInput::combine(
            $this->displayable_keys,
            $this->displayable_values
        );
    }

    protected function getCreateRedirect() 
    {
        return $this->redirectable? $this->getTable(). '.create': '';
    }

    protected function getEditRedirect() 
    {
        return $this->redirectable? $this->getTable(). '.edit': '';   
    }
        

    
}
