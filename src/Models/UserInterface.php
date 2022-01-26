<?php

namespace Raydragneel\HerauthLib\Models;

use Raydragneel\HerauthLib\Entities\AccountEntity;

interface UserInterface
{

    public function cekUser(string $username);
    public function attempt(AccountEntity $entity);
}
