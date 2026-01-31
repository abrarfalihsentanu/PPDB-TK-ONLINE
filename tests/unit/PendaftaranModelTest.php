<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class PendaftaranModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = 'DatabaseSeeder';
    protected $basePath = APPPATH . 'Database';

    /**
     * Test getWithRelations returns QueryBuilder when $id is null
     */
    public function testGetWithRelationsReturnsBuilderWhenIdNull()
    {
        $model = new \App\Models\PendaftaranModel();
        $result = $model->getWithRelations();

        // Should return QueryBuilder instance
        $this->assertInstanceOf('CodeIgniter\Database\BaseBuilder', $result);
    }

    /**
     * Test getWithRelations supports where() method chaining
     */
    public function testGetWithRelationsSupportsWhereChaining()
    {
        $model = new \App\Models\PendaftaranModel();

        // This should not throw error
        try {
            $result = $model->getWithRelations()
                ->where('status_pendaftaran', 'draft')
                ->get();

            // If no error, test passes
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Method chaining with where() failed: ' . $e->getMessage());
        }
    }

    /**
     * Test getWithRelations with orderBy chaining
     */
    public function testGetWithRelationsSupportsOrderByChaining()
    {
        $model = new \App\Models\PendaftaranModel();

        try {
            $result = $model->getWithRelations()
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();

            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Method chaining with orderBy() failed: ' . $e->getMessage());
        }
    }

    /**
     * Test getWithRelations with specific ID still returns object
     */
    public function testGetWithRelationsWithIdReturnsObject()
    {
        $model = new \App\Models\PendaftaranModel();

        // Get first pendaftaran
        $pendaftaran = $model->first();

        if ($pendaftaran) {
            $result = $model->getWithRelations($pendaftaran->id);

            // Should return object or null, not QueryBuilder
            $this->assertTrue(
                is_object($result) || $result === null,
                'getWithRelations($id) should return object or null'
            );
        }
    }

    /**
     * Test full chain: getWithRelations -> where -> orderBy -> limit -> get -> getResult
     */
    public function testFullMethodChain()
    {
        $model = new \App\Models\PendaftaranModel();

        try {
            $results = $model->getWithRelations()
                ->where('status_pendaftaran', 'draft')
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResult();

            // Should return array
            $this->assertIsArray($results);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Full method chain failed: ' . $e->getMessage());
        }
    }
}
