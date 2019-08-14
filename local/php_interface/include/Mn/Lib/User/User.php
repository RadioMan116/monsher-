<?php

class MnLibUserUser
{
    public static function getById($id)
    {
        $dbUser = CUser::GetByID($id);
        if ($arUser = $dbUser->Fetch()) return $arUser;
    }
}
