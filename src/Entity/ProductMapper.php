<?php

namespace kollex\Entity;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use kollex\DataProvider\Assortment\Product;
use PDOException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

class ProductMapper implements RepositoryInterface
{

    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManager $em, LoggerInterface $logger = null)
    {
        $this->em = $em;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param Product[] $items
     * @throws RuntimeException
     */
    public function saveMany(array $items): void
    {
        foreach ($items as $e) {
            try {
                if ($this->exists($e)) {
                    $this->logger->error(sprintf("Skipping product %s, it exists already", $e->id));

                }
                $this->em->persist($e);
            } catch (ORMException $ex) {
                throw new RuntimeException('Failed to save entity', 0, $ex);
            }
        }

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $uniq) {
            $this->logger->warning("Failed to import(s). Product(s) exists already");
        } catch (ORMException $ex) {
            throw new RuntimeException('Failed to persist entities', 0, $ex);
        } catch (PDOException $pdo) {
            throw new RuntimeException('Failed to persist entities (pdo)', 0, $pdo);
        } catch (\Exception $e) {
            throw new RuntimeException("Unhandled exception....", 0, $e);
        }
    }

    private function exists(Product $e): bool
    {
        $repo = $this->em->getRepository(ProductEntity::class);
        $item = $repo->findOneBy(['id' => $e->id]);
        return ! empty($item);
    }

    /**
     * @return Product[]
     */
    public function retrieveAll(): array
    {
        return $this->em->getRepository(ProductEntity::class)->findAll();
    }
}
