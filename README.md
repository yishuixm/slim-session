# slim

// session
$container['session'] = function () {
    return new yishuixm\slim\Session\Helper;
};

// 加入SESSION中间件
$app->add(new yishuixm\slim\Session\Middleware([
    'name'          => 'PHPSESSION',
    'domain'        => '',
    'autorefresh'   => true,
    'lifetime'      => '1 hour'
]));