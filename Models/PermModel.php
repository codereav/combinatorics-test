<?php
/**
 * Created by PhpStorm.
 * User: codereav
 * Date: 17.05.2019
 * Time: 13:26
 */

namespace App\Models;

use Symfony\Component\HttpFoundation\Request;
use wrossmann\PCIters\CombinationIterator;

/**
 * Class PermModel
 * @package App\Models
 */
class PermModel implements PermModelInterface
{
    /**
     * @var int $fieldsCount
     */
    private $fieldsCount;
    /**
     * @var int $chipsCount
     */
    private $chipsCount;
    /**
     * @var CombinationIterator $combination
     */
    private $combination;

    /**
     * PermModel constructor.
     * @param Request $request
     * @param CombinationIterator $combination
     */
    public function __construct(Request $request, CombinationIterator $combination)
    {
        $fieldsCount = (int)$request->get('fieldsCount');
        $chipsCount = (int)$request->get('chipsCount');

        if ($this->validate($request->getMethod(), $fieldsCount, $chipsCount)) {

            $this->setFieldsCount($fieldsCount);
            $this->setChipsCount($chipsCount);
            $this->combination = $combination;

        } else {
            die('Неверные входные данные!');
        }
    }

    /**
     * @param string $requestMethod
     * @param int $fieldsCount
     * @param int $chipsCount
     * @return bool
     */
    public function validate(string $requestMethod, int $fieldsCount, int $chipsCount): bool
    {
        return ($requestMethod === 'POST' && $fieldsCount && $chipsCount && $fieldsCount >= $chipsCount);
    }

    /**
     * @param int $fieldsCount
     */
    private function setFieldsCount(int $fieldsCount): void
    {
        $this->fieldsCount = $fieldsCount;
    }

    /**
     * @param int $chipsCount
     */
    private function setChipsCount(int $chipsCount): void
    {
        $this->chipsCount = $chipsCount;
    }

    /**
     * @return int
     */
    public function getFieldsCount(): int
    {
        return $this->fieldsCount;
    }

    /**
     * @return int
     */
    public function getChipsCount(): int
    {
        return $this->chipsCount;
    }


    /**
     * @return \Generator
     */
    public function getData(): \Generator
    {
        return $this->combination::iterate(range(1, $this->getFieldsCount()), $this->getChipsCount());
    }

    /**
     * @param string $filename
     */
    public function writeDataToFile(string $filename): void
    {
        $combsCount = 0;

        $file = fopen($filename, 'w+');
        fwrite($file, 'Кол-во вариантов: ' . $combsCount . PHP_EOL);

        foreach ($this->getData() as $row) {
            $combsCount++;
            fwrite($file, json_encode($row), strlen(json_encode($row)));
        }
        fseek($file, 0);
        fwrite($file, 'Кол-во вариантов: ' . $combsCount);

        fclose($file);
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function deleteDataFile(string $filename): bool
    {
        return unlink($filename);
    }


}