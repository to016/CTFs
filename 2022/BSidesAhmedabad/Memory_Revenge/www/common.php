<?php

function _RunMagicQuotes(&$svar)
{
    if (!@get_magic_quotes_gpc())
    {
        if (is_array($svar))
        {
            foreach ($svar as $_k => $_v)
            {
                $svar[$_k] = _RunMagicQuotes($_v);
            }

        }
        else
        {
            if (strlen($svar) > 0 && preg_match('#^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_SESSION)#', $svar))
            {
                exit('Request var not allow!');
            }
            $svar = addslashes($svar);
        }
    }

    return $svar;
}

function CheckRequest(&$val)
{
    if (is_array($val))
    {
        foreach ($val as $_k => $_v)
        {
            if ($_k == 'nvarname')
            {
                continue;
            }

            CheckRequest($_k);
            CheckRequest($val[$_k]);
        }
    }
    else
    {
        if (strlen($val) > 0 && preg_match('#^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_SESSION)#', $val))
        {
            exit('Request var not allow!');
        }
    }
}

CheckRequest($_REQUEST);
CheckRequest($_COOKIE);

foreach (array('_GET','_POST','_COOKIE') as $_request)
{
    foreach ($$_request as $_k => $_v)
    {
        ${$_k} = _RunMagicQuotes($_v);
    }
}

?>
