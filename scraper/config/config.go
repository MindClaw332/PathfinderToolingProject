package config

import (
	"log"
	"os"

	"github.com/joho/godotenv"
)

type Config struct {
	Host     string
	Port     string
	Database string
	Username string
	Password string
}

// get the config from the .env

func GetConfig() (*Config, error) {
	// if this is executed in the subfolder of the project look up one directory otherwise look inside this directory
	err := godotenv.Load("../.env")
	if err != nil {
		err := godotenv.Load(".env")
		if err != nil {
			log.Fatal(err)
		}
	}
	// error if the connection is not mysql
	if os.Getenv("DB_CONNECTION") != "mysql" {
		log.Printf("Wrong database driver need mysql has: %v", os.Getenv("DB_CONNECTION"))
	}

	// add it to a memory address we can return and modify later if necessary
	cfg := &Config{
		Host:     os.Getenv("DB_HOST"),
		Port:     os.Getenv("DB_PORT"),
		Database: os.Getenv("DB_DATABASE"),
		Username: os.Getenv("DB_USERNAME"),
		Password: os.Getenv("DB_PASSWORD"),
	}
	return cfg, nil
}
