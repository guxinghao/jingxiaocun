<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/home.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts.src.js"></script>
<div class="main">
	<!-- 顶部 -->
	<div class="top">
		<div class="item">
			<a href="<?php echo checkOperation('销售单') ? Yii::app()->createUrl('frmSales/index') : ''; ?>">
				<img src="<?php echo imgURL('sale_list.png'); ?>" alt="" /><br />
				<span>销售单</span>
			</a>
		</div>
		<div class="item">
			<a href="<?php echo checkOperation('库存管理') ? Yii::app()->createUrl('mergeStorage/locklist') : ''; ?>">
				<img src="<?php echo imgURL('store_manage.png'); ?>" alt="" /><br />
				<span>库存管理</span>
			</a>
		</div>
		<div class="item">
			<a href="<?php echo checkOperation('往来汇总') ? Yii::app()->createUrl('turnover/totalNew') : ''; ?>">
				<img src="<?php echo imgURL('sale_summary.png'); ?>" alt="" /><br />
				<span>往来汇总</span>
			</a>
		</div>
		<!-- <div class="item">
			<a href="<?php //echo checkOperation('采购汇总') ? Yii::app()->createUrl('purchase/purchaseData') : ''; ?>">
				<img src="<?php //echo imgURL('purchase_summary.png'); ?>" alt="" /><br />
				<span>采购汇总</span>
			</a>
		</div> -->
		<div class="item">
			<a href="<?php echo checkOperation('资金汇总') ? Yii::app()->createUrl('frmBillLog/total') : ''; ?>">
				<img src="<?php echo imgURL('fund_summary.png'); ?>" alt="" /><br />
				<span>资金汇总</span>
			</a>
		</div>
		<div class="item">
			<a href="<?php echo checkOperation('资金明细') ? Yii::app()->createUrl('frmBillLog/index') : ''; ?>">
				<img src="<?php echo imgURL('fund_detail.png'); ?>" alt="" /><br />
				<span>资金明细</span>
			</a>
		</div>
	</div>
	<!-- 左部 -->
	<div class="left">
		<div class="left_top">
			<div class="item1">
				<a href="<?php echo checkOperation('销售退货') ? Yii::app()->createUrl('salesReturn/index') : ''; ?>">
					<img src="<?php echo imgURL('sale_return.png'); ?>" alt="" /><br />
					<span>销售退货</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('盘盈盘亏') ? Yii::app()->createUrl('frmPypk/index') : ''; ?>">
					<img src="<?php echo imgURL('win_loss.png'); ?>" alt="" /><br />
					<span>盘盈盘亏</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('开票汇总') ? Yii::app()->createUrl('detailForInvoice/index') : ''; ?>">
					<img src="<?php echo imgURL('invoice_summary.png'); ?>" alt="" /><br />
					<span>开票汇总</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('采购运费') ? Yii::app()->createUrl('billRecord/index?type=purchase') : ''; ?>">
					<img src="<?php echo imgURL('purchase_fee.png'); ?>" alt="" /><br />
					<span>采购运费</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('钢厂返利') ? Yii::app()->createUrl('billRebate/index?type=supply') : ''; ?>">
					<img src="<?php echo imgURL('mill_rebate.png'); ?>" alt="" /><br />
					<span>钢厂返利</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('仓库返利') ? Yii::app()->createUrl('billRebate/index?type=warehouse') : ''; ?>">
					<img src="<?php echo imgURL('store_rebate.png'); ?>" alt="" /><br />
					<span>仓库返利</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('仓储费用') ? Yii::app()->createUrl('billRebate/index?type=cost') : ''; ?>">
					<img src="<?php echo imgURL('store_fee.png'); ?>" alt="" /><br />
					<span>仓库费用</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('采购销票') ? Yii::app()->createUrl('purchaseInvoice/index') : ''; ?>">
					<img src="<?php echo imgURL('purchase_ticket.png'); ?>" alt="" /><br />
					<span>采购销票</span>
				</a>
			</div>
			<div class="item1">
				<a href="<?php echo checkOperation('合同报表') ? Yii::app()->createUrl('contract/dataTable') : ''; ?>">
					<img src="<?php echo imgURL('contract_summary.png'); ?>" alt="" /><br />
					<span>合同汇总</span>
				</a>
			</div>
		</div>
		<div class="left_middle div_table">
			<?php $this->widget('DataTableWdiget', array(
					'id' =>'indextable',
					'tableHeader'=>$tableHeader,
					'tableData'=>$tableData,
					'totalData'=>$totalData,
					'hide'=>1
				)); ?>
		</div>
		<div class="left_bottom div_table">
			<?php $this->widget('DataTableWdiget', array(
					'id' =>'indextable1',
					'tableHeader'=>$tableHeader1,
					'tableData'=>$tableData1,
					'totalData'=>$totalData1,
					'hide'=>1
				)); ?>
		</div>
	</div>
	<!-- 右部 -->
	<div class="right">
		<div id="container" style="float:left;margin:0 auto;width:100%;"></div>
		<div class="chart_list">
			<?php foreach($detail as $k => $v){ ?>
				<div class="item2">
					<div class="icon"></div>
					<span class="name"><?php echo $k; ?></span>
					<span class="weight"><?php echo (float)$v ? number_format($v, 3) : 0; ?>吨</span>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('#indextable').datatable({
		fixedLeftWidth:70,
		fixedRightWidth:0,
	});
	$('#indextable1').datatable({
		fixedLeftWidth:70,
		fixedRightWidth:0,
	});
});

// 饼图设置和数据
var data = <?php echo $chart; ?>;
$(function () {
    $(document).ready(function () {
        // Build the chart
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}<b>{point.percentage:.2f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true,
                }
            },
            series: [{
                name: ' ',
                colorByPoint: true,
                data: data
            }]
        });
    });
});
</script>