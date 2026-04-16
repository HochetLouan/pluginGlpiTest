<?php

namespace GlpiPlugin\Myplugin;


class PluginReservation{
    private ?int $id = null;
    private ?string $item = null;
    private ?DateTime $baselinedate = null;
    private ?DateTime $mailingdate = null;
    private ?DateTime $checkindate = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getItem(): ?string {
        return $this->item;
    }

    public function getBaselineDate(): ?DateTime {
        return $this->baselinedate;
    }

    public function getMailingDate(): ?DateTime {
        return $this->mailingdate;
    }

    public function getCheckinDate(): ?DateTime {
        return $this->checkindate;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function setItem(string $item): self {
        $this->item = $item;
        return $this;
    }

    public function setBaselineDate(DateTime $baselinedate): self {
        $this->baselinedate = $baselinedate;
        return $this;
    }

    public function setMailingDate(DateTime $mailingdate): self {
        $this->mailingdate = $mailingdate;
        return $this;
    }

    public function setCheckinDate(DateTime $checkindate): self {
        $this->checkindate = $checkindate;
        return $this;
    }

    public function hydrate(array $data): self {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this;
    }
}

class PluginReservationMenu extends CommonGLPI {

   static function getMenuName() {
      return "Test";
   }

   static function getMenuContent() {
      return [
         'title' => self::getMenuName(),
         'page'  => '/plugins/test/front/index.php',
         'icon'  => 'fas fa-puzzle-piece'
      ];
   }
}