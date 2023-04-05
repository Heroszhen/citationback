<?php

namespace App\Entity;

use App\Repository\CitationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\CitationImageController;



/**
 * //"security"="is_granted('ROLE_USER')",
 * @ORM\Entity(repositoryClass=CitationRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"citation:read"}},
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "denormalization_context"={"groups"={"citation:write"}},
 *              "controller"=CitationImageController::class,
 *              "deserialize"=false
 *          }
 *      },
 *      itemOperations={
 *          "get",
 *          "put"={
 *              "security"="is_granted('ROLE_USER') or object.user == user",
 *              "denormalization_context"={"groups"={"citation:write"}}
 *          }
 *      }
 * )
 */
class Citation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"citation:read", "citation:write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="citations")
     * @Groups({"citation:read", "citation:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="text", options={"default": ""})
     * @Groups({"citation:read", "citation:write"})
     */
    private $message = "";

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"citation:read"})
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="citation")
     */
    private $comments;

    /**
     * @var File
     * 
     * @Vich\UploadableField(mapping="citation_images", fileNameProperty="filename", size="filesize", mimeType="filetype", originalName="filename2")
     * 
     * @Assert\File(
     *     maxSize = "4M",
     *     maxSizeMessage="Le fichier est trop volumineux",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage = "Veuillez envoyer une image au format jpeg, png ou gif."
     * )
     * 
     * @Groups({"citation:write"})
     */
    public $image;

    /**
     * @ORM\Column(type="string", length=255, options={"default": ""})
     * @Groups({"citation:read"})
     */
    private $filename = "";

    /**
     * @ORM\Column(type="string", length=255, options={"default": ""})
     * @Groups({"citation:read"})
     */
    private $filetype = "";

    /**
     * @ORM\Column(type="string", length=255, options={"default": ""})
     * @Groups({"citation:read"})
     */
    private $filename2 = "";

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     * @Groups({"citation:read"})
     */
    private $filesize = 0;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCitation($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCitation() === $this) {
                $comment->setCitation(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;
        if($image !== null){
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFiletype(): ?string
    {
        return $this->filetype;
    }

    public function setFiletype(string $filetype): self
    {
        $this->filetype = $filetype;

        return $this;
    }

    public function getFilename2(): ?string
    {
        return $this->filename2;
    }

    public function setFilename2(string $filename2): self
    {
        $this->filename2 = $filename2;

        return $this;
    }

    public function getFilesize(): ?int
    {
        return $this->filesize;
    }

    public function setFilesize(int $filesize): self
    {
        $this->filesize = $filesize;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
