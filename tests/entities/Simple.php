<?php declare(strict_types = 1);

use WebChemistry\DoctrineHydration\MagicHydration;

/**
 * @Entity()
 */
class Simple {

	use MagicHydration;

	/**
	 * @var int
	 * @Column(type="integer", nullable=FALSE)
	 * @Id
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @var string
	 * @Column(type="string", length=120)
	 */
	protected $name;

	/**
	 * @var string
	 * @Column(type="string", length=120)
	 */
	protected $position;

	/**
	 * @var int|null
	 * @Column(type="integer", nullable=true)
	 */
	protected $nullable;

	public function __construct(string $name, string $position, ?int $nullable = null) {
		$this->name = $name;
		$this->position = $position;
		$this->nullable = $nullable;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPosition(): string {
		return $this->position;
	}

	/**
	 * @return int|null
	 */
	public function getNullable(): ?int {
		return $this->nullable;
	}

	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $position
	 */
	public function setPosition(string $position): void {
		$this->position = $position;
	}

	/**
	 * @param int|null $nullable
	 */
	public function setNullable(?int $nullable): void {
		$this->nullable = $nullable;
	}

}
