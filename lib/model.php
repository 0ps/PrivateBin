<?php
/**
 * PrivateBin
 *
 * a zero-knowledge paste bin
 *
 * @link      https://github.com/PrivateBin/PrivateBin
 * @copyright 2012 Sébastien SAUVAGE (sebsauvage.net)
 * @license   http://www.opensource.org/licenses/zlib-license.php The zlib/libpng License
 * @version   0.22
 */

/**
 * model
 *
 * Factory of PrivateBin instance models.
 */
class model
{
    /**
     * Configuration.
     *
     * @var configuration
     */
    private $_conf;

    /**
     * Data storage.
     *
     * @var privatebin_abstract
     */
    private $_store = null;

    /**
     * Factory constructor.
     *
     * @param configuration $conf
     */
    public function __construct(configuration $conf)
    {
        $this->_conf = $conf;
    }

    /**
     * Get a paste, optionally a specific instance.
     *
     * @param string $pasteId
     * @return model_paste
     */
    public function getPaste($pasteId = null)
    {
        $paste = new model_paste($this->_conf, $this->_getStore());
        if ($pasteId !== null) $paste->setId($pasteId);
        return $paste;
    }

    /**
     * Gets, and creates if neccessary, a store object
     */
    private function _getStore()
    {
        if ($this->_store === null)
        {
            // added option to support old config file format
            $model = str_replace(
                'zerobin_', 'privatebin_',
                $this->_conf->getKey('class', 'model')
            );
            $this->_store = forward_static_call(
                array($model, 'getInstance'),
                $this->_conf->getSection('model_options')
            );
        }
        return $this->_store;
    }
}
