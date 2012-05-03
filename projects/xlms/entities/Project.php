<?php
/**
 * @Entity @Table(name="projects")
 **/
class Project extends Entity
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $name;

}

