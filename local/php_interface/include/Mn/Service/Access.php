<?php

class MnAccess
{
    public function __construct()
    {
        $catalogAccess = $this->checkCatalogAccess();

        if (!$catalogAccess)
        {
            include($_SERVER["DOCUMENT_ROOT"]."/404.php");
            exit();
        }
    }

    public function checkCatalogAccess()
    {
        $cleanUri = MnLibRequestRequest::getUriWithoutParams();

        global $USER;
        $userGroups = CUser::GetUserGroup($USER->GetID());

        //@todo path to const
        //@todo now all is open for /catalog/cart/
        if ($cleanUri == '/catalog/' && !$USER->IsAdmin()) return true;
        elseif (substr($_SERVER['REQUEST_URI'], 0, 9) == '/catalog/' && substr($_SERVER['REQUEST_URI'], 0, 14) != '/catalog/cart/')
        {
            $uriArray = MnLibRequestRequest::getUriWithoutParams('array');
            $shopCode = $uriArray[1];

            //@todo id to const
            $shop = MnLibElementElement::getByIBlockId(50, array('CODE' => $shopCode));
            if (!MnModelShopShop::userHasAccess($shop[0]['ID'])) return false;
            else return true;
        }
        else return true;
    }
}
