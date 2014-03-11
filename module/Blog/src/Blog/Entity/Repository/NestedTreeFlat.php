<?php
namespace Blog\Entity\Repository;

class NestedTreeFlat extends \Gedmo\Tree\Entity\Repository\NestedTreeRepository
{
    protected $locale;
    protected $firstResult;
    protected $maxResults;

    public function getTreeAsFlatArray(array $nodes = array(), $depth = null)
    {
        if (null === $depth) {
            $depth = 0;
            $nodes = $this->buildTree($this->getNestedTree());
        }
        $return = array();
        $children = null;
        foreach ($nodes as $node) {
            $children = $node['__children'];
            unset($node['__children']);
            $return[] = $node;
            if (null !== $children) {
                $return = array_merge($return, $this->getTreeAsFlatArray($children, $depth + 1));
            }
            $children = null;
        }
        return $return;
    }


    public function getNestedTree($asScalar=false)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select((($asScalar)? 'count(node)' : 'node'))
            ->from($this->getEntityName(), 'node');

        if ($this->hasLocale()) {
            $queryBuilder->where('node.locale = ?1')
                ->setParameter(1, $this->getLocale());
        }
        if ($asScalar) {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        }

        $queryBuilder->orderBy('node.root, node.lft', 'ASC');
        
        if ($this->hasFirstResult()) {
            $queryBuilder->setFirstResult($this->getFirstResult());
        }
        if ($this->hasMaxResults()) {
            $queryBuilder->setMaxResults($this->getMaxResults());
        }

        $query = $queryBuilder->getQuery();
        $query->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true);
        return $query->getArrayResult();
    }

    public function getNestedTreeTotalCount()
    {
        return $this->getNestedTree($asScalar=true);
    }

    public function getFromIds(array $ids)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('node')
                ->from($this->getEntityName(), 'node');

        $paramNumber = 1;
        if ($this->hasLocale()) {
            $queryBuilder->where('node.locale = ?' . $paramNumber)
                ->setParameter($paramNumber++, $this->getLocale());
        }

        foreach ($ids as $id) {
            $queryBuilder->orWhere('node.id = ?' . $paramNumber)
                ->setParameter($paramNumber++, $id);
        }

        $queryBuilder->orderBy('node.root, node.lft', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function unsetLocale()
    {
        return $this->setLocale(null);
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    public function getLocale()
    {
        if (!$this->hasLocale()) {
            throw new \Exception('Locale is not set');
        }
        return $this->locale;
    }

    public function hasLocale()
    {
        return null !== $this->locale;
    }

    public function setFirstResult($offset)
    {
        $this->firstResult = $offset;
        return $this;
    }

    public function getFirstResult()
    {
        return $this->firstResult;
    }

    public function hasFirstResult()
    {
        return null !== $this->maxResults;
    }

    public function setMaxResults($limit)
    {
        $this->maxResults = $limit;
        return $this;
    }

    public function getMaxResults()
    {
        return $this->maxResults;
    }

    public function hasMaxResults()
    {
        return null !== $this->maxResults;
    }

    /**
     * Entities can be linked to a translated entity
     * The translated returned must be the same for
     * all entities when if fetched from some.
     * If no entity has a transalted associated, then
     * the returned translated is simple a brand new one.
     */
    public function getNewOrUniqueReusedTranslated(array $entities)
    {
        $translated = null;
        //Reuse translated if entity is associated to one already
        foreach ($entities as $entity) {
            if (null !== $translated && $entity->hasTranslated() && $translated !== $entity->getTranslated()) {
                throw new \Exception('In the posts you selected, at least two are already a translation of different translated. If you pursue, one of both translated, will have to be deleted and all posts being a translation of the deleted translated will have to be updated, are you sure this is the behaviour you want? If so, implement it...');
            }
            if ($entity->hasTranslated()) {
                $translated = $entity->getTranslated();
            }
        }

        // Get new translation
        if (null === $translated) {
            $translated = $entity->getTranslated();
        }
        return $translated;
    }
}
