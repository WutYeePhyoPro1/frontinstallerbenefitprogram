<?php

use App\Models\InstallerCard;

function getAuthCard(){
    $installer_card_card_number = Session()->get('installer_card_card_number');
    $installercard = InstallerCard::where('card_number',$installer_card_card_number)->first();

    return $installercard;
}
