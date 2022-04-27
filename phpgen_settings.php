<?php

//  define('SHOW_VARIABLES', 1);
//  define('DEBUG_LEVEL', 1);

//  error_reporting(E_ALL ^ E_NOTICE);
//  ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';
include_once dirname(__FILE__) . '/' . 'components/mail/mailer.php';
include_once dirname(__FILE__) . '/' . 'components/mail/phpmailer_based_mailer.php';
require_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';

//  SystemUtils::DisableMagicQuotesRuntime();

SystemUtils::SetTimeZoneIfNeed('America/Argentina/Buenos_Aires');

function GetGlobalConnectionOptions()
{
    return
        array(
          'server' => 'ufpa.cjkvtoasluvj.us-east-1.rds.amazonaws.com',
          'port' => '3306',
          'username' => 'root',
          'password' => 'vacinacao123',
          'database' => 'sistema_controle_animais',
          'client_encoding' => 'utf8'
        );
}

function HasAdminPage()
{
    return true;
}

function HasHomePage()
{
    return true;
}

function GetHomeURL()
{
    return 'index.php';
}

function GetHomePageBanner()
{
    return '';
}

function GetPageGroups()
{
    $result = array();
    $result[] = array('caption' => 'Para Uso do Orgão Responsavel', 'description' => '');
    $result[] = array('caption' => 'Sistema de Cadastro', 'description' => '');
    return $result;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => 'Proprietario', 'short_caption' => 'Proprietario', 'filename' => 'proprietario.php', 'name' => 'proprietario', 'group_name' => 'Sistema de Cadastro', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Animal', 'short_caption' => 'Animal', 'filename' => 'animal.php', 'name' => 'animal', 'group_name' => 'Sistema de Cadastro', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Vacina', 'short_caption' => 'Vacina', 'filename' => 'vacina.php', 'name' => 'vacina', 'group_name' => 'Para Uso do Orgão Responsavel', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Campanha Vacinacao', 'short_caption' => 'Campanha Vacinacao', 'filename' => 'campanha_vacinacao.php', 'name' => 'campanha_vacinacao', 'group_name' => 'Para Uso do Orgão Responsavel', 'add_separator' => false, 'description' => '');
    return $result;
}

function GetPagesHeader()
{
    return
        '<span class="navbar-brand">

    <span>

        <span class="hidden-xs"><strong>SimPet | </strong></span>

    </span>

</span>

<span class="navbar-brand">    

    <span class="hidden-xs"><strong>Sistema de Controle de Vacina de Animais Domésticos</strong></span>

</span>';
}

function GetPagesFooter()
{
    return
        '';
}

function ApplyCommonPageSettings(Page $page, Grid $grid)
{
    $page->SetShowUserAuthBar(true);
    $page->setShowNavigation(true);
    $page->OnGetCustomExportOptions->AddListener('Global_OnGetCustomExportOptions');
    $page->getDataset()->OnGetFieldValue->AddListener('Global_OnGetFieldValue');
    $page->getDataset()->OnGetFieldValue->AddListener('OnGetFieldValue', $page);
    $grid->BeforeUpdateRecord->AddListener('Global_BeforeUpdateHandler');
    $grid->BeforeDeleteRecord->AddListener('Global_BeforeDeleteHandler');
    $grid->BeforeInsertRecord->AddListener('Global_BeforeInsertHandler');
    $grid->AfterUpdateRecord->AddListener('Global_AfterUpdateHandler');
    $grid->AfterDeleteRecord->AddListener('Global_AfterDeleteHandler');
    $grid->AfterInsertRecord->AddListener('Global_AfterInsertHandler');
}

function GetAnsiEncoding() { return 'windows-1252'; }

function Global_AddEnvironmentVariablesHandler(&$variables)
{

}

function Global_CustomHTMLHeaderHandler($page, &$customHtmlHeaderText)
{

}

function Global_GetCustomTemplateHandler($type, $part, $mode, &$result, &$params, CommonPage $page = null)
{

}

function Global_OnGetCustomExportOptions($page, $exportType, $rowData, &$options)
{

}

function Global_OnGetFieldValue($fieldName, &$value, $tableName)
{

}

function Global_GetCustomPageList(CommonPage $page, PageList $pageList)
{

}

function Global_BeforeInsertHandler($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
{

}

function Global_BeforeUpdateHandler($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
{

}

function Global_BeforeDeleteHandler($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
{

}

function Global_AfterInsertHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function Global_AfterUpdateHandler($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function Global_AfterDeleteHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function GetDefaultDateFormat()
{
    return 'd-m-Y';
}

function GetFirstDayOfWeek()
{
    return 0;
}

function GetPageListType()
{
    return PageList::TYPE_SIDEBAR;
}

function GetNullLabel()
{
    return null;
}

function UseMinifiedJS()
{
    return true;
}

function GetOfflineMode()
{
    return false;
}

function GetInactivityTimeout()
{
    return 0;
}

function GetMailer()
{
    $mailerOptions = new MailerOptions(MailerType::Sendmail, '', '');
    
    return PHPMailerBasedMailer::getInstance($mailerOptions);
}

function sendMailMessage($recipients, $messageSubject, $messageBody, $attachments = '', $cc = '', $bcc = '')
{
    GetMailer()->send($recipients, $messageSubject, $messageBody, $attachments, $cc, $bcc);
}

function createConnection()
{
    $connectionOptions = GetGlobalConnectionOptions();
    $connectionOptions['client_encoding'] = 'utf8';

    $connectionFactory = MyPDOConnectionFactory::getInstance();
    return $connectionFactory->CreateConnection($connectionOptions);
}

/**
 * @param string $pageName
 * @return IPermissionSet
 */
function GetCurrentUserPermissionsForPage($pageName) 
{
    return GetApplication()->GetCurrentUserPermissionSet($pageName);
}
