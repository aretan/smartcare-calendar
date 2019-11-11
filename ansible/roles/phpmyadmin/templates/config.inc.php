<?php

$cfg['Servers'][1]['auth_type'] = 'cookie';
$cfg['Servers'][1]['host'] = '{{ mysql_hostname }}';
$cfg['Servers'][1]['user'] = '{{ mysql_username }}';
$cfg['Servers'][1]['only_db'] = ['{{ mysql_database }}'];
$cfg['Servers'][1]['compress'] = false;
$cfg['Servers'][1]['AllowNoPassword'] = false;
$cfg['Servers'][1]['AllowRoot'] = false;

$cfg['Servers'][1]['pmadb'] = 'phpmyadmin';
$cfg['Servers'][1]['bookmarktable'] = 'pma__bookmark';
$cfg['Servers'][1]['relation'] = 'pma__relation';
$cfg['Servers'][1]['table_info'] = 'pma__table_info';
$cfg['Servers'][1]['table_coords'] = 'pma__table_coords';
$cfg['Servers'][1]['pdf_pages'] = 'pma__pdf_pages';
$cfg['Servers'][1]['column_info'] = 'pma__column_info';
$cfg['Servers'][1]['history'] = 'pma__history';
$cfg['Servers'][1]['table_uiprefs'] = 'pma__table_uiprefs';
$cfg['Servers'][1]['tracking'] = 'pma__tracking';
$cfg['Servers'][1]['userconfig'] = 'pma__userconfig';
$cfg['Servers'][1]['recent'] = 'pma__recent';
$cfg['Servers'][1]['favorite'] = 'pma__favorite';
$cfg['Servers'][1]['users'] = 'pma__users';
$cfg['Servers'][1]['usergroups'] = 'pma__usergroups';
$cfg['Servers'][1]['navigationhiding'] = 'pma__navigationhiding';
$cfg['Servers'][1]['savedsearches'] = 'pma__savedsearches';
$cfg['Servers'][1]['central_columns'] = 'pma__central_columns';
$cfg['Servers'][1]['designer_settings'] = 'pma__designer_settings';
$cfg['Servers'][1]['export_templates'] = 'pma__export_templates';

$cfg['blowfish_secret'] = '{{ lookup("password", "/dev/null length=50 chars=ascii_letters") }}';
$cfg['MaxRows'] = 100;
$cfg['DefaultLang'] = 'ja';
$cfg['QueryHistoryDB'] = true;
$cfg['QueryHistoryMax'] = 100;
$cfg['DisableShortcutKeys'] = true;
$cfg['VersionCheck'] = false;
$cfg['enable_drag_drop_import'] = false;
$cfg['LoginCookieValidity'] = 14400;
$cfg['LoginCookieStore'] = 14400;
$cfg['TempDir'] = '/tmp';
