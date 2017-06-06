<?php

namespace HLIN601\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HLIN601UserBundle extends Bundle
{
	public function getParent()
  	{
    	return 'FOSUserBundle';
  	}
}
