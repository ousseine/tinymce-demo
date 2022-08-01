<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 100)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostAttachment::class)]
    private Collection $postAttachments;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->postAttachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection<int, PostAttachment>
     */
    public function getPostAttachments(): Collection
    {
        return $this->postAttachments;
    }

    public function addPostAttachment(PostAttachment $postAttachment): self
    {
        if (!$this->postAttachments->contains($postAttachment)) {
            $this->postAttachments->add($postAttachment);
            $postAttachment->setPost($this);
        }

        return $this;
    }

    public function removePostAttachment(PostAttachment $postAttachment): self
    {
        if ($this->postAttachments->removeElement($postAttachment)) {
            // set the owning side to null (unless already changed)
            if ($postAttachment->getPost() === $this) {
                $postAttachment->setPost(null);
            }
        }

        return $this;
    }
}
