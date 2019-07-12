<?if(!$USER->IsAdmin()) return;//use Bitrix\Main\Diag\Debug;IncludeModuleLangFile(__FILE__);if ( ! CModule::IncludeModule('asdaff.recaptcha') )	return (false);//Restore defaultsif ($_SERVER["REQUEST_METHOD"]=="POST" && strlen($RestoreDefaults)>0 && check_bitrix_sessid()){	COption::RemoveOption("asdaff.recaptcha");}$arAllOptions =	Array("act", "key", "secretkey", "theme", "size", "badge", "mask_exclusion");$aTabs = array();$arSitesParam = array();$sites_	= CSite::getList($by="sort", $order="asc", Array());while($site	= $sites_->fetch()){    $arSitesParam[] = $site;    $aTabs[] = array('DIV' => 'recaptcha_site_settings_' . $site['LID'], 'TAB' => $site['NAME'] . ' (' . $site['LID'] . ')', 'ICON' => 'settings', 'TITLE' => GetMessage('GCT_SETTINGS') . $site['NAME'] . ' (' . $site['LID'] . ')');}//Save optionsif($_SERVER["REQUEST_METHOD"]=="POST" ){	foreach($arSitesParam as $site){		$arSettings	= Array();		foreach($arAllOptions as $option){			if (isset($_POST[$option."_".$site['LID']])){				$arSettings[$option]	= $_POST[$option."_".$site['LID']];			}		}        //Debug::dump($arSettings);		COption::SetOptionString("asdaff.recaptcha", "settings", serialize($arSettings), false, $site['LID']);	}}$tabControl = new CAdminTabControl("tabControl", $aTabs);$tabControl->Begin();?><form name="recaptcha_settings" method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?echo LANG?>">	<?foreach($arSitesParam as $site):?>		<?$tabControl->BeginNextTab();?>		<h4><?=GetMessage("GCT_LINK_RECAPTCHA")?></h4>		<?$settings	= COption::GetOptionString("asdaff.recaptcha", "settings", false, $site['LID']);		if(!$settings){			$arSettings	= array();		}else{			$arSettings	= unserialize($settings);		}        if(empty($arSettings['mask_exclusion'])){ // default mask            $arSettings['mask_exclusion'] = '/bitrix/*;/404.php;/upload/*;/cgi-bin/*;/local/*';        }?>		<tr>			<td><?=GetMessage('GCT_ACT');?></td>			<td><input name="act_<?=$site['LID'];?>" type="checkbox" <?=$arSettings['act']=='Y'?'checked="true"':'';?> value="Y"/></td>		</tr>		<tr>			<td><?=GetMessage('GCT_KEY');?></td>			<td><input name="key_<?=$site['LID'];?>" type="text" size="45" value="<?=$arSettings['key'];?>"/></td>		</tr>		<tr>			<td><?=GetMessage('GCT_SECRETKEY');?></td>			<td><input name="secretkey_<?=$site['LID'];?>" type="text" size="45" value="<?=$arSettings['secretkey'];?>"/></td>		</tr>		<tr>			<td><?=GetMessage('GCT_THEME');?></td>			<td>				<select name="theme_<?=$site['LID'];?>">					<option <?=$arSettings['theme']=='light'?'selected':'';?> value="light"><?=GetMessage('GCT_THEME_LIGHT');?></option>					<option <?=$arSettings['theme']=='dark'?'selected':'';?> value="dark"><?=GetMessage('GCT_THEME_DARK');?></option>				</select>			</td>		</tr>        <tr>			<td><?=GetMessage('GCT_SIZE');?></td>			<td>				<select id="el_size_<?=$site['LID'];?>" name="size_<?=$site['LID'];?>">					<option <?=$arSettings['size']=='normal'?'selected':'';?> value="normal"><?=GetMessage('GCT_SIZE_NORMAL');?></option>					<option <?=$arSettings['size']=='compact'?'selected':'';?> value="compact"><?=GetMessage('GCT_SIZE_COMPACT');?></option>					<option <?=$arSettings['size']=='invisible'?'selected':'';?> value="invisible"><?=GetMessage('GCT_SIZE_INVISIBLE');?></option>				</select>                <script type="text/javascript">                    document.getElementById("el_size_<?=$site['LID'];?>").onchange = function(e){                        var badge = document.getElementById("container_badge_<?=$site['LID'];?>");                        if(e.target.value === "invisible"){                            badge.style.display = "table-row";                        }else{                            badge.style.display = "none";                        }                    };                </script>			</td>		</tr>        <tr id="container_badge_<?=$site['LID'];?>" <?if($arSettings['size']!=='invisible'):?> style="display: none;"<?endif;?>>			<td><?=GetMessage('GCT_BADGE');?></td>			<td>				<select name="badge_<?=$site['LID'];?>">					<option <?=$arSettings['badge']=='bottomright'?'selected':'';?> value="bottomright">bottomright</option>					<option <?=$arSettings['badge']=='bottomleft'?'selected':'';?> value="bottomleft">bottomleft</option>                    <option <?=$arSettings['badge']=='inline'?'selected':'';?> value="inline">inline</option>				</select>			</td>		</tr        <tr>			<td><?=GetMessage('GCT_MASK_EXCLUSION');?></td>			<td>                <textarea name="mask_exclusion_<?=$site['LID'];?>" style="width:100%" ><?=$arSettings['mask_exclusion'];?></textarea>			</td>		</tr>	<?endforeach;	$tabControl->Buttons(array());	$tabControl->End();	?></form>