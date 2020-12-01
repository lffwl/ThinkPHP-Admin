<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class CreateSql extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('CreateSql')
            ->setDescription('生成数据库');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $sql = root_path() . 'thinkphp-adimn.sql';
        exec("mysql -h " . config('database.connections.mysql.hostname') . " --user=" . config('database.connections.mysql.username') . " --password=" . config('database.connections.mysql.password') . " --database=" . config('database.connections.mysql.database') . " -e 'source " . $sql . "' ");
        $output->writeln("生成数据库成功");
    }
}
