<?php
namespace Scs\Req;

class Scs extends \Gbili\Db\Req\AbstractReq
{
    public function loadKeyedFields(array $keys = array())
    {
        return array(
                'post_category_slug' => 'p.category_slug', 
                'post_id' => 'p.id',
                'post_slug' => 'p.slug',
                'post_translated_id' => 'p.translatedpost_id',
                'post_title' => 'pd.title',
                'post_content' => 'pd.content',
                'post_date' => 'pd.date',
                'post_locale' => 'p.locale',

                'media_src' => 'concat(m.publicdir, "/", m.slug)',

                'file_dirpath' => 'f.dirpath',
                'file_basename' => 'f.basename',
                
                'user_uniquename' => 'u.uniquename',
        );
    }

    public function getBaseSqlString()
    {
        return 'SELECT ' . "\n"
                . $this->getFieldAsKeyString() 
            . ' FROM posts AS p ' . "\n"
                // For parent post slug and lvl1 category
                . ' LEFT JOIN posts AS parent_p ON p.parent_id = parent_p.id ' . "\n"
                . ' LEFT JOIN post_datas AS parent_pd ON parent_p.data_id = parent_pd.id ' . "\n"
                // For child post slug and lvl1 category 
                . ' LEFT JOIN posts AS child_p ON p.root = child_p.root AND p.lvl + 1 = child_p.lvl' . "\n"
                . ' LEFT JOIN post_datas AS child_pd ON child_p.data_id = child_pd.id ' . "\n"
                // For the rest of the post info 
                . ' LEFT JOIN users AS u ON p.user_id = u.id ' . "\n"
                . ' LEFT JOIN post_datas AS pd ON p.data_id = pd.id ' . "\n"
                . ' LEFT JOIN medias AS m ON pd.media_id = m.id '  . "\n"
                . ' LEFT JOIN files AS f ON m.file_id = f.id '  . "\n";
    }

    public function getTrailingSql()
    {
        return ' GROUP BY p.id';
    }

    public function getPostsWithLevel1Category(array $criteria = array())
    {
        $this->addKeyedField('parent_post_count', 'count(parent_p.id)');
        $this->addKeyedField('parent_post_slug', 'parent_p.slug');
        $this->addKeyedField('parent_post_title', 'parent_pd.title');
        $this->addKeyedField('parent_post_category_slug', 'parent_p.category_slug'); 
        $this->addKeyedField('child_post_count', 'count(child_p.id)');
        $this->addKeyedField('child_post_slug', 'child_p.slug');
        $this->addKeyedField('child_post_category_slug', 'child_p.category_slug'); 

        return $this->getResultSetByCriteria($this->getBaseSqlString(), $criteria, $this->getTrailingSql());
    }

    public function getPostsByLocaleInCategoriesLikeQuery($locale, array $allowedCategories, $query=null)
    {
        $this->addKeyedField('value', 'p.slug'); //get post_slug AS value instead
        $conditions = [];
        $conditions[] = ['post_locale' => ['=' => $locale]];

        if (empty($allowedCategories)) {
            return array(); //no parent posts allowed
        }

        if (null !== $query) {
            $conditions[] = ['post_slug' => ['like' => '%' . $query . '%']];
        }

        $categoryConditions = [];
        foreach ($allowedCategories as $categorySlug) {
            $categoryConditions[] = ['post_category_slug' => ['=' => $categorySlug]];
        }

        $conditions['or'] = $categoryConditions;

        return $this->getPosts(['and' => $conditions], 
            array('post_id', 'value') //get post_slug AS value
        );
    }

    public function getPosts(array $criteria=array() , array $keys=array('post_id', 'post_slug'))
    {
        return $this->getResultSetByCriteria("SELECT {$this->getFieldAsKeyString($keys)} FROM posts AS p", $criteria);
    }
}
