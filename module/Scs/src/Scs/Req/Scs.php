<?php
namespace Scs\Req;

class Scs extends \Gbili\Db\Req\AbstractReq
{
    public function loadKeyedFields()
    {
        return array(
                'category_id' => 'c.id', 
                'category_slug' => 'c.slug', 
                'category_name' => 'c.name', 
                'category_lvl' => 'c.lvl', 
                'category_lft' => 'c.lft', 
                'category_rgt' => 'c.rgt', 

                'lvl1_category_id' => 'pc.id', 
                'lvl1_category_slug' => 'pc.slug', 
                'lvl1_category_name' => 'pc.name', 
                'lvl1_category_lvl' => 'pc.lvl', 
                'lvl1_category_lft' => 'pc.lft', 
                'lvl1_category_rgt' => 'pc.rgt', 

                'post_id' => 'p.id',
                'post_slug' => 'p.slug',
                'post_translated_id' => 'p.translatedpost_id',
                'post_title' => 'pd.title',
                'post_content' => 'pd.content',
                'post_date' => 'pd.date',
                'post_locale' => 'p.locale',

                'media_alt' => 'm.alt',

                'file_dirpath' => 'f.dirpath',
                'file_basename' => 'f.basename',
                
                'owner_uniquename' => 'u.uniquename',
        );
    }

    public function getBaseSqlString()
    {
        $categoryTrunkSql = ' LEFT JOIN categories AS %spc ON %spc.lft <= %sc.lft AND %spc.rgt >= %sc.rgt AND %spc.lvl = 1 AND %spc.root = %sc.root';

        return 'SELECT ' . "\n"
                . $this->getFieldAsKeyString() 
            . ' FROM posts AS p ' . "\n"
                // For parent post slug and lvl1 category
                . ' LEFT JOIN posts AS parent_p ON p.parent_id = parent_p.id ' . "\n"
                . ' LEFT JOIN post_datas AS parent_pd ON parent_p.data_id = parent_pd.id ' . "\n"
                . ' LEFT JOIN categories AS parent_c ON parent_p.category_id = parent_c.id ' . "\n"
                . str_replace('%s', 'parent_', $categoryTrunkSql) . "\n"
                // For child post slug and lvl1 category 
                . ' LEFT JOIN posts AS child_p ON p.root = child_p.root AND p.lvl + 1 = child_p.lvl' . "\n"
                . ' LEFT JOIN post_datas AS child_pd ON child_p.data_id = child_pd.id ' . "\n"
                . ' LEFT JOIN categories AS child_c ON child_p.category_id = child_c.id ' . "\n"
                . str_replace('%s', 'child_', $categoryTrunkSql) . "\n"
                // For the rest of the post info 
                . ' LEFT JOIN users AS u ON p.user_id = u.id ' . "\n"
                . ' LEFT JOIN post_datas AS pd ON p.data_id = pd.id ' . "\n"
                . ' LEFT JOIN medias AS m ON pd.media_id = m.id '  . "\n"
                . ' LEFT JOIN files AS f ON m.file_id = f.id '  . "\n"
                . ' LEFT JOIN categories AS c ON p.category_id = c.id '  . "\n"
                . str_replace('%s', '', $categoryTrunkSql) . "\n";
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
        $this->addKeyedField('parent_lvl1_category_slug', 'parent_pc.slug'); 
        $this->addKeyedField('parent_lvl1_category_name', 'parent_pc.name'); 
        $this->addKeyedField('child_post_count', 'count(child_p.id)');
        $this->addKeyedField('child_post_slug', 'child_p.slug');
        $this->addKeyedField('child_lvl1_category_slug', 'child_pc.slug'); 
        $this->addKeyedField('child_lvl1_category_name', 'child_pc.name'); 

        return $this->getResultSetByCriteria($this->getBaseSqlString(), $criteria, $this->getTrailingSql());
    }
}
