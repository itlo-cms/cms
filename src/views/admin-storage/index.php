<?php
/**
 * @author Logachev Roman <rlogachev@itlo.ru>
 * @link http://itlo.ru/
 * @copyright ITLO (Infomarket)
 */
?>
<h2><?= \Yii::t('itlo/cms', 'Servers to store the files.') ?></h2>
<?php foreach (\Yii::$app->storage->getClusters() as $count => $cluster) : ?>
    <div class="sx-box g-pa-10 sx-bg-primary">
        <div class="row">
            <div class="col-md-12">
                <h3><b><?= is_numeric($count )?$count:0+ 1; ?>. <?= $cluster->name; ?></b></h3>
            </div>
            <div class="col-md-6">
                <p><b><?= \Yii::t('itlo/cms', 'Public file path') ?>: </b> <?= $cluster->publicBaseUrl; ?></p>
                <p><b><?= \Yii::t('itlo/cms', 'The folder on the server') ?>: </b> <?= $cluster->rootBasePath; ?></p>

                <p><b><?= \Yii::t('itlo/cms',
                            'Total available space') ?></b>: <?= Yii::$app->formatter->asShortSize($cluster->getTotalSpace()); ?>
                </p>
                <p><b><?= \Yii::t('itlo/cms',
                            'Used') ?></b>: <?= Yii::$app->formatter->asShortSize($cluster->getUsedSpace()); ?></p>
                <p><b><?= \Yii::t('itlo/cms',
                            'Free') ?></b>: <?= Yii::$app->formatter->asShortSize($cluster->getFreeSpace()); ?></p>

                <?php if ($cluster instanceof \itlo\cms\components\storage\ClusterLocal) : ?>
                    <?php if ($cluster->publicBaseUrlIsAbsolute) : ?>
                        <p><b><?= \Yii::t('itlo/cms', 'Files download from domain') ?>
                                : </b> <?= $cluster->publicBaseUrlIsAbsolute; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <?

                $baseOptions =
                    [
                        'title' => ['text' => \Yii::t('itlo/cms', 'At percent ratio')],
                        'chart' => [
                            'type' => 'pie',

                        ],
                        'plotOptions' =>
                            [
                                'pie' =>
                                    [
                                        'allowPointSelect' => 'true',
                                        'cursor' => "pointer",
                                        'depth' => 35,
                                        'dataLabels' =>
                                            [
                                                'enabled' => 'true',
                                                'format' => '{point.name}',
                                            ]
                                    ]
                            ],
                        'series' => [
                            [
                                'type' => 'pie',
                                'name' => '%',
                                'data' =>
                                    [
                                        [\Yii::t('itlo/cms', 'Free') . " " . round($cluster->getFreeSpacePct()) . "%", round($cluster->getFreeSpacePct())],
                                        [\Yii::t('itlo/cms', 'Used') . " " . round($cluster->getUsedSpacePct()) . "%", round($cluster->getUsedSpacePct())],
                                    ]

                            ],
                        ]
                    ];
                echo \itlo\widget\highcharts\Highcharts::widget(['options' => $baseOptions]);

                ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
