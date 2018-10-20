<?php

namespace App\Admin\Controllers;

use App\Models\ShopProduct;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Columms;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ShopProductsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ShopProduct);
        $grid->id('ID');
        // $grid->column('name', 'Username');
        $grid->sku('ABC');
        $grid->type('Release?')->display(function ($released) {
            return $released ? 'yes' : 'no';
        });
        $grid->filter(function ($filter) {

            // Sets the range query for the created_at field
            $filter->between('created_at', 'Created Time')->datetime();
        });
        $grid->name()->display(function ($name) {
            return "<span class='btn btn-success'>$name</span>";
        });
        $grid->sku()->display(function ($email) {
            return "mailto:$email";
        });
        // $grid->perPages([10, 20, 30, 40, 50]);
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ShopProduct::findOrFail($id));

        $show->id('ID');
        $show->name('Name');
        $show->sku('Sku');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ShopProduct);

        $form->display('ID');
        $form->display('Name');
        $form->display('Sku');

        return $form;
    }
}
