<?php

$_lang['bbbx'] = 'BBBx';
$_lang['bbbx.menu_desc'] = 'BigBlueButton manager';

$_lang['setting_bbbx.server_url'] = 'BigBlueButton Server URL';
$_lang['setting_bbbx.server_url_desc'] = 'The URL of your BigBlueButton server. (Default: <a href="http://test-install.blindsidenetworks.com/bigbluebutton/" target="_blank">http://test-install.blindsidenetworks.com/bigbluebutton/</a> , provided by <a href="http://blindsidenetworks.com/" target="_blank">Blindside Networks</a> that you can use for testing.)';
$_lang['setting_bbbx.shared_secret'] = 'BigBlueButton Shared Secret';
$_lang['setting_bbbx.shared_secret_desc'] = 'The security salt of your BigBlueButton server. (Default: 8cd8ef52e8e101574e400365b55e11a6 , provided by <a href="http://blindsidenetworks.com/" target="_blank">Blindside Networks</a> that you can use for testing.)';
$_lang['setting_bbbx.waitformoderator_ping_interval'] = 'Wait for moderator ping (seconds)';
$_lang['setting_bbbx.waitformoderator_ping_interval_desc'] = 'When the wait for moderator feature is enabled, the client pings for the status of the session each [number] seconds. This parameter defines the interval for requests made to the server. Default: 10';
$_lang['setting_bbbx.waitformoderator_cache_ttl'] = 'Wait for moderator cache TTL (seconds)';
$_lang['setting_bbbx.waitformoderator_cache_ttl_desc'] = 'To support a heavy load of clients this plugin makes use of a cache. This parameter defines the time the cache will be kept before the next request is sent to the BigBlueButton server. Default: 60';
$_lang['setting_bbbx.moderator_default'] = 'Moderator by default';
$_lang['setting_bbbx.moderator_default_desc'] = 'This rule is used by default when a new room or conference is added. Multiple: comma separated usergroups\' names. Default: Administrator';
