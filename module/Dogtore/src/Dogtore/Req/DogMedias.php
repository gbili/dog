<?php
namespace Dogtore\Req;

class DogMedias extends \Gbili\Db\Req\AbstractReq
{
    public function loadKeyedFields()
    {
        return array(
            'user_uniquename' => 'u.uniquename',
            'dog_name' => 'd.name',
            'media_alt' => 'mm.alt',
            'media_slug' => 'm.slug',
            'media_src' => 'concat(m.publicdir, "/", m.slug)',
        );
    }

    public function getBaseSqlString()
    {
        return 'SELECT ' 
                . $this->getFieldAsKeyString() 
            . ' FROM medias m '
                . ' LEFT JOIN dogs AS d ON m.dog_id = d.id '
                . ' LEFT JOIN users AS u ON d.user_id = u.id '
                . ' LEFT JOIN media_metadatas AS mm ON m.id = mm.media_id ';
    }

    public function getTrailingSql()
    {
        return ' GROUP BY m.id'
            . ' ORDER BY m.date DESC';
    }

    public function getMedias(array $criteria = array())
    {
        return $this->getResultSetByCriteria($this->getBaseSqlString(), $criteria, $this->getTrailingSql());
    }
}
