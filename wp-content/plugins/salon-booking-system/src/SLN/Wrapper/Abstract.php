<?php

abstract class SLN_Wrapper_Abstract
{
    protected $object;

    abstract public function getPostType();

    function __construct($object)
    {
        
        if (!is_object($object)) {
            $object = get_post($object);
        }
        if(SLN_Helper_Multilingual::isMultilingual() ){
            $this->translationObjectId = $object->ID;
            $this->translationObject = $object;
            if(in_array(get_post_type( $object ),['sln_service','sln_attendant']) && !empty($object->ID) ){
                $defaultLanguage = SLN_Helper_Multilingual::getDefaultLanguage();
                $objectLanguage = SLN_Helper_Multilingual::getObjectLanguage($this->translationObjectId);
                if($defaultLanguage !== $objectLanguage ){
                    $object  = get_post(SLN_Helper_Multilingual::translateId($this->translationObjectId));
                }
            }
        }
        $this->object = $object;
    }

    public function reload(){
        $this->object = get_post($this->getId());
        if(SLN_Helper_Multilingual::isMultilingual()){
            $this->translationObject = get_post(($this->translationObjectId));
        }
    }

    function getId()
    {
        if ($this->object) {
            return $this->object->ID;
        }
    }

    public function isEmpty()
    {
        return empty($this->object);
    }

    public function getMeta($key, $targetTranslation = false )
    {
        $pt = $this->getPostType();

        $id = $targetTranslation && SLN_Helper_Multilingual::isMultilingual() ? $this->translationObjectId : $this->getId();

        return apply_filters("$pt.$key.get", get_post_meta($id, "_{$pt}_$key", true));
    }

    public function setMeta($key, $value, $targetTranslation = false )
    {
        $pt = $this->getPostType();
        $id = $targetTranslation && SLN_Helper_Multilingual::isMultilingual()  ? $this->translationObjectId : $this->getId();
        update_post_meta($id, "_{$pt}_$key", apply_filters("$pt.$key.set", $value));
    }

    public function getStatus()
    {
        return $this->object->post_status;
    }

    public function hasStatus($status)
    {
        return SLN_Func::has($this->getStatus(), $status);
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $post = array();
        $post['ID'] = $this->getId();
        $post['post_status'] = $status;
        wp_update_post($post);
        $this->object->post_status = $status;

        return $this;
    }

    public function getTitle()
    {
        $object = SLN_Helper_Multilingual::isMultilingual()  ? $this->translationObject : $this->object;
        if ($object) {
            return $object->post_title;
        }
    }

    public function getPostDate()
    {
        if ($this->object) {
            return SLN_TimeFunc::getPostDateTime($this->object);
        }
    }

    public function getExcerpt()
    {
        $object = SLN_Helper_Multilingual::isMultilingual()  ? $this->translationObject : $this->object;
        if ($object) {
            return $object->post_excerpt;
        }
    }
}
