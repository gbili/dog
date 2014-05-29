<?php
namespace Dogtore\Req;

class Dog extends \Gbili\Db\Req\AbstractReq
{
    public function loadKeyedFields()
    {
        return array(
            'user_uniquename' => 'u.uniquename',
            'user_id' =>'u.id',
            'dog_id' => 'd.id',
            'dog_name' => 'd.name',
            'dog_locale' => 'd.locale',
            'dog_breed' => 'd.breed',
            'dog_color' => 'd.color',
            'dog_gender' => 'd.gender',
            'dog_weightkg' => 'd.weightkg',
            'dog_birthdate' => 'd.birthdate',
            'dog_whythisdog' => 'd.whythisdog',

            'media_alt' => 'mm.alt',
            'media_src' => 'concat(m.publicdir, "/", m.slug)',
        );
    }

    public function getBaseSqlString()
    {
        return 'SELECT ' 
                . $this->getFieldAsKeyString() 
            . ' FROM users u '
                . ' LEFT JOIN user_data AS ud ON u.data_id = ud.id '
                . ' RIGHT JOIN dogs AS d ON ud.id = d.userdata_id '
                . ' LEFT JOIN medias AS m ON d.media_id = m.id '
                . ' LEFT JOIN media_metadatas AS mm ON m.id = mm.media_id ';
    }

    public function getTrailingSql()
    {
        return ' GROUP BY d.id';
    }

    public function getDogs(array $criteria = array())
    {
        return $this->getResultSetByCriteria($this->getBaseSqlString(), $criteria, $this->getTrailingSql());
    }
}
