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

func GetConfig() (*Config, error) {
	err := godotenv.Load("../.env")
	if err != nil {
		err := godotenv.Load(".env")
		if err != nil {
			log.Fatal(err)
		}
	}

	if os.Getenv("DB_HOST") != "mysql" {
		log.Printf("Wrong database driver need mysql has: %v", os.Getenv("DB_HOST"))
	}

	cfg := &Config{
		Host:     os.Getenv("DB_HOST"),
		Port:     os.Getenv("DB_PORT"),
		Database: os.Getenv("DB_DATABASE"),
		Username: os.Getenv("DB_USERNAME"),
		Password: os.Getenv("DB_PASSWORD"),
	}
	return cfg, nil
}
