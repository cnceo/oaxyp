<?php 
define('Copyright', 'Author QQ: 1234567');
define('ROOT_PATH', $_SERVER["DOCUMENT_ROOT"].'/');
include_once ROOT_PATH.'functioned/cheCookie.php';
global $user, $UserOut, $stratGamenc, $endGamenc;

$dateTime = date('Y-m-d H:i:s');
$a = date('Y-m-d ').'02:03:00';
if ( ($dateTime < $stratGamenc && $dateTime > $a) || $dateTime > $endGamenc){
	back('開盤時間為：'.$stratGamenc.'--'.$endGamenc);exit;
}

if ($user[0]['g_look'] == 2) exit(back($UserOut));
if ($_SERVER["REQUEST_METHOD"] != "POST") {exit;}
$guid = sha1(uniqid(time(),TRUE));
$_SESSION['guid_code'] = $guid;
if ($user[0]['g_out'] != 1) exit(back($UserOut));
$s_type =  $_POST['gg'];
$s_number = $_GET['v'];
$s_ball_arr = $_POST['t'];
sort($s_ball_arr);
$n = 'h'.trim(strtr($s_type, "t"," "));
$odds = $odds = GetOdds ('連碼', $n,9); //獲取賠率
$ConfigModel= configModel("`g_max_money`, `g_mix_money`, `g_odds_ratio_b1`,`g_odds_ratio_b2`,`g_odds_ratio_b3`,`g_odds_ratio_b4`,`g_odds_ratio_b5`,`g_odds_ratio_c1`,`g_odds_ratio_c2`,`g_odds_ratio_c3`,`g_odds_ratio_c4`,`g_odds_ratio_c5`");
$odds = setodds($n, $odds, $ConfigModel, $user, 2);
$stringList = GetGameType($s_type);
//復式計算、返回值、【總組數】、【總個數】
$results = subArr ($s_ball_arr, $stringList['count']);
$result = GetUserXianEr ($stringList['type'], null, $user[0]['g_name']);
$max = GetUser_s ($result, $user,$stringList['type'],null);
$max1 = $max['DanZhu_XianEr']; //單注限額
$max2 = $max['DanHao_XianE']; //單號限額
$max3 = $max['DanHao_YiXia']; //單號已下
$max4 = $max['DanQi_XianEr']; //單期限額
$max5 = $max['DanQi_YiXia']; //單期已下
$gMoney = $max['KeYongEr']; //可用額

for ($i=0; $i<count($s_ball_arr); $i++)
{
	$s_ball_arr[$i] = mb_strlen($s_ball_arr[$i]) <=1 ? '0'.$s_ball_arr[$i] : $s_ball_arr[$i];
}
$str = join('、', $s_ball_arr); //號碼
$nor = $gMoney / $results[0]; //總金額/總組數=單筆下注金額
if (strpos($nor, '.'))
{
	$a = explode('.', $nor);
	$nor =$a[0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" oncontextmenu="return false">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="css/left.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="./js/sc.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="./js/sGet_nc.js"></script>
<style type="text/css">
body {background-color:#FFEFE2}
</style>
</head>
<body onselectstart="return false">
<form id="dp" action="" method="post">
<input type="hidden" name="actions" value="fn2" />
<input type="hidden" name="gtypes" value="1" />
<input type="hidden" name="s_type" value="<?php echo base64_encode($s_type)?>" />
<input type="hidden" name="s_number" value="<?php echo base64_encode($s_number)?>" />
<input type="hidden" name="s_ball" value="<?php echo base64_encode($str)?>" />
					<table border="0" cellpadding="0" cellspacing="1" class="t_list" width="230" style="top:0px;left:0px;">
                    <tr>
                        <td class="t_list_caption" colspan="2"><span><?php echo $stringList['type']?></span> - 下註</td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">會員帳戶</td>
                        <td class="t_td_text" width="137"><?php echo $user[0]['g_name']?>（<?php echo $user[0]['g_panlu']?>盤）</td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1">可用金額</td>
                        <td class="t_td_text"><?php echo is_Number($gMoney)?></td>
                    </tr>
                    <tr>
                    	<td class="t_td_text" colspan="2" align="center" style="background:#FFFFF0">
                        	<span style="color:#009933; font-weight:bold"><?php echo $s_number?>期</span><br />
                            <span style="color:#0000FF"><?php echo $stringList['type']?></span>@ 
                            <span style="color:red; font-weight:bold;font-size:14px" id="odds"><?php echo $odds?></span><br />
                            <span style="color:#0000FF;font-weight:bold;line-height:25px">下註號碼明細</span><br />
                            <span style="color:#0099FF;font-size:14px"><?php echo $str?></span><br /><br />
                            <span>您共選擇了<span style="color:red"><?php echo $results[2]?></span> 個號碼</span><br />
                            <span>“復式”共分為<span style="color:red" id="gCount"><?php echo $results[0]?></span> 組</span><br />
                            <span>每組最高可下註金額 <span style="color:red" id="maxMoney"><?php echo $nor?></span> 元</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">每註金額</td>
                        <td class="t_td_text" width="137"><input type="text" class="inp1" name="s_money" id="money" onkeyup="onlys(this)" onfocus="this.className='inp1m'" onblur="this.className='inp1';" maxlength="9" /></td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">下註總計</td>
                        <td class="t_td_text" width="137" id="countOdds">0</td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">最高派彩</td>
                        <td class="t_td_text" width="137" id="pc"><?php echo $ConfigModel['g_max_money']?></td>
                    </tr>
                    <tr>
					  <td class="t_td_caption_1" width="64">最低下注</td>
                        <td class="t_td_text" width="137" id="mix"><?php echo $ConfigModel['g_mix_money']?></td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">单注限额</td>
                        <td class="t_td_text" width="137" id="max1"><?php echo $max1?></td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">当天限额</td>
                        <td class="t_td_text" width="137" id="max2"><?php echo $max2?></td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">当天已下</td>
                        <td class="t_td_text" width="137" id="max3"><?php echo $max3?></td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">单号限额</td>
                        <td class="t_td_text" width="137" id="max4"><?php echo $max4?></td>
                    </tr>
                    <tr>
                        <td class="t_td_caption_1" width="64">单号已下</td>
                        <td class="t_td_text" width="137" id="max5"><?php echo $max5?></td>
						</tr>
                    <tr>
                    	<td class="t_td_but" colspan="2" align="center">
                        	<input type="button" value="取消" onclick="location.href='left.php'" class="inputq" />&nbsp;&nbsp;
                            <input type="submit" value="下注" id="submitv" class="inputq" />
                            <input type="hidden" name="s_cq" class="actiionn" value="<?php echo $s_cq[0][0].','.$s_cq[0][1]?>" />
                        </td>
                    </tr>
                </table>
                </form>
</body>
</html>