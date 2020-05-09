<?php
// INN validator (10-digit for legals, 12-digits for person)
//  in rules()   [['inn'], 'app\components\validators\validateInn'],
// for test purpose use fake INN number 1234567894 or 123456789110

namespace feldwebel\validators;

use yii\validators\Validator;
use Yii;

class InnValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $inn = $model->$attribute;
        $len = strlen($inn);
        if (($len !== 12) && ($len !== 10)) {
            $this->addError($model, $attribute, \Yii::t('validate', 'Incorrect INN length'));
        }
        
        if ($len === 10 && ($inn[9] !== (string) (((
                2*$inn[0] + 4*$inn[1] + 10*$inn[2] + 
                3*$inn[3] + 5*$inn[4] +  9*$inn[5] + 
                4*$inn[6] + 6*$inn[7] +  8*$inn[8]
                ) % 11) % 10))) { 
            $this->addError($model, $attribute, \Yii::t('validate', 'Incorrect INN checksum'));
            
        } elseif ($len === 12 && (($inn[10] !== (string) (((
                7*$inn[0] + 2*$inn[1] + 4*$inn[2] +
               10*$inn[3] + 3*$inn[4] + 5*$inn[5] + 
                9*$inn[6] + 4*$inn[7] + 6*$inn[8] +
                8*$inn[9]
                ) % 11) % 10) || ($inn[11] !== (string) (((
                3*$inn[0] +  7*$inn[1] + 2*$inn[2] +
                4*$inn[3] + 10*$inn[4] + 3*$inn[5] +
                5*$inn[6] +  9*$inn[7] + 4*$inn[8] +
                6*$inn[9] +  8*$inn[10]
                ) % 11) % 10))))) {            
            $this->addError($model, $attribute, \Yii::t('validate', 'Incorrect INN checksum'));
        }
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations() {
        if (!isset(Yii::$app->get('i18n')->translations['validate*'])) {
            Yii::$app->get('i18n')->translations['validate'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US'
            ];
        }
    }
}
