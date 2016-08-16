<?php
namespace Fab\WebService\UserFunction;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Controller class for handling action related to a product.
 */
class RealUrl implements SingletonInterface
{

    /**
     * @param $params
     * @param $ref
     * @return bool|int|string
     */
    public function getDataType($params, $ref)
    {

        var_dump($params);
        $result = $params['origValue'];
        if ($params['origValue'] == 'processes') {
            $result = 'tx_bobst_processes';
        }

        return $result;
    }


}