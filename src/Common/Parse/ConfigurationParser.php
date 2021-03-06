<?php

namespace Zend\Mvc\OIDC\Common\Parse;


use Zend\Mvc\OIDC\Common\Configuration;
use Zend\Mvc\OIDC\Common\Enum\ConfigurationEnum;
use Zend\Mvc\OIDC\Common\Exceptions\AudienceConfigurationException;
use Zend\Mvc\OIDC\Common\Exceptions\RealmConfigurationException;
use Zend\Mvc\OIDC\Common\Exceptions\ServiceUrlConfigurationException;

/**
 * Class ConfigurationParser
 *
 * @package Zend\Mvc\OIDC\Common\Parse
 */
class ConfigurationParser
{

    /**
     * @param array $configurationArray
     *
     * @return Configuration|null
     * @throws RealmConfigurationException
     * @throws ServiceUrlConfigurationException
     * @throws AudienceConfigurationException
     */
    public function parse(array $configurationArray): ?Configuration
    {
        if (isset($configurationArray[ConfigurationEnum::AUTH_SERVICE])) {
            $this->applyValidations($configurationArray[ConfigurationEnum::AUTH_SERVICE]);

            /** @var array $config */
            $config = $configurationArray[ConfigurationEnum::AUTH_SERVICE];

            $configuration = new Configuration();
            $configuration->setAuthServiceUrl($config[ConfigurationEnum::AUTH_SERVICE_URL]);
            $configuration->setRealmId($config[ConfigurationEnum::REALM_ID]);
            $configuration->setClientId($config[ConfigurationEnum::CLIENT_ID]);
            $configuration->setAudience($config[ConfigurationEnum::AUDIENCE]);

            return $configuration;
        }

        return null;
    }

    /**
     * @param array $configuration
     *
     * @throws RealmConfigurationException
     * @throws ServiceUrlConfigurationException
     * @throws AudienceConfigurationException
     */
    private function applyValidations(array $configuration): void
    {
        $this->hasAuthServiceUrlConfiguration($configuration);

        $this->hasRealmIdConfiguration($configuration);

        $this->hasAudienceConfiguration($configuration);
    }

    /**
     * @param array $configuration
     *
     * @throws ServiceUrlConfigurationException
     */
    private function hasAuthServiceUrlConfiguration(array $configuration): void
    {
        if (!isset($configuration[ConfigurationEnum::AUTH_SERVICE_URL])
            || $configuration[ConfigurationEnum::AUTH_SERVICE_URL] == null
            || $configuration[ConfigurationEnum::AUTH_SERVICE_URL] == '') {
            throw new ServiceUrlConfigurationException('There is no Realm definitions in configuration');
        }
    }

    /**
     * @param array $configuration
     *
     * @throws RealmConfigurationException
     */
    private function hasRealmIdConfiguration(array $configuration): void
    {
        if (!isset($configuration[ConfigurationEnum::REALM_ID])
            || $configuration[ConfigurationEnum::REALM_ID] == null
            || $configuration[ConfigurationEnum::REALM_ID] == '') {
            throw new RealmConfigurationException('There is no Realm definitions in configuration');
        }
    }

    /**
     * @param array $configuration
     *
     * @throws AudienceConfigurationException
     */
    private function hasAudienceConfiguration(array $configuration): void
    {
        if (!isset($configuration[ConfigurationEnum::AUDIENCE])
            || $configuration[ConfigurationEnum::AUDIENCE] == null
            || $configuration[ConfigurationEnum::AUDIENCE] == '') {
            throw new AudienceConfigurationException('There is no Audience definitions in configuration');
        }
    }


}