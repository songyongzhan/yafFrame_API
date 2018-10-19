<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:15
 * Email: songyongzhan@qianbao.com
 */

return [
  'Application'=>[
    'directory'=>APPLICATION_PATH.'/app',
    'dispatcher'=>[
      'catchException'=>true
    ],
    'view'=>[
      'ext'=>'html'
    ],
    'system'=>[
      'environ'=>ENVIRONMENT
    ]
  ],
  'twig'=>[
    'cache'=>APPLICATION_PATH.'/data/cache',
    'autoescape'=>true,
    'debut'=>true
  ]
];