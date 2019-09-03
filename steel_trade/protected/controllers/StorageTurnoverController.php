<?php

/**
 * 库存流水报表
 * @category I_Don't_Know.
 * @package  I_Don't_Know.
 * @author   yy_prince   <gengzicong@xun-ao.com>
 * @license  http://www.xun-ao.com YY_prince_license
 * @link     http://www.xun-ao.com
 */

/**
 * 库存流水报表
 * @category I_Don't_Know.
 * @package  I_Don't_Know.
 * @author   yy_prince   <gengzicong@xun-ao.com>
 * @license  http://www.xun-ao.com YY_prince_license
 * @link     http://www.xun-ao.com
 */
class StorageTurnoverController extends AdminBaseController
{

    public $layout = 'admin';

    /**
     * 列表页
     * @return 那啥
     */
    public function actionIndex()
    {
        $this->pageTitle = "库存流水报表";
        // $this->setHome = 1;//允许设为首页
        list ($model, $search, $pages, $items) = StorageTurnoverView::getIndexList();
        $titles = DictTitle::getComs("json");
        $products = DictGoodsProperty::getProList("product", "", "");
        $textures = DictGoodsProperty::getProList("texture", "", "");
        $ranks = DictGoodsProperty::getProList("rank", "", "");
        $brands = DictGoodsProperty::getProList("brand", "", "");
        $warehouse = Warehouse::getWareList("json");
        $this->render(
            'index', array(
            'search' => $search,
            'pages' => $pages,
            'items' => $items,
            'model' => $model,
            'titles' => $titles,
            'targets' => $targets,
            'products' => $products,
            'textures' => $textures,
            'ranks' => $ranks,
            'brands' => $brands,
            'st_time' => $st_date,
            'et_time' => $et_date,
            'warehouse' => $warehouse)
        );
    }
}