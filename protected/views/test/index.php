<hr />
如果这个页面出现，则意味着uws + distmem + php_distmem + usPHP初步运行成功！
<br />
<fieldset>
    <legend>介绍</legend>
    <ul>
        <li>uws: 基础web服务器</li>
        <li>distmem: 简单的NoSQL数据库</li>
        <li>php_distmem: php程序连接distmem的C扩展</li>
        <li>usPHP: 一个简单的PHP MVC框架</li>
    </ul>
</fieldset>
<h4>distmem测试</h4>
<table border="1px">
<tr> <td> 待储存的数据 </td>
<td> 取出的数据</td></tr>
<tr><td>
<?php
    var_dump($after);
?>
</td><td>
<?php
    var_dump($after);
?>
</td></tr> </table>
<h4>uws变量</h4>
<?php
    var_dump($_SERVER);
?>
