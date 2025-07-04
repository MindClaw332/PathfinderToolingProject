package main

import (
	"database/sql"
	"fmt"
	"log"
	"slices"
	"strconv"
	"strings"
	"time"

	"github.com/MindClaw332/PathfinderToolingProject/config"
	"github.com/MindClaw332/PathfinderToolingProject/helper"
	"github.com/go-rod/rod"
	_ "github.com/go-sql-driver/mysql"
)

type Creature struct {
	name       string
	size       string
	level      int
	hp         int
	ac         int
	fortitude  int
	reflex     int
	will       int
	perception int
	senses     string
	speed      string
	rarity     string
	traits     []string
}

func browseToPage(browser *rod.Browser, link string) *rod.Page {
	// Create a new page
	page := browser.MustPage(link).MustWaitStable()
	fmt.Println("page visited")
	return page
}

// depending on the index decide what we want to do, if the table changes this code needs to change
func parseTableData(index int, creature *Creature, data *rod.Element, sizes []string, rarities []string) error {
	switch index {
	// first element is always the nalme of the creature
	case 0:
		creature.name = data.MustElement("a").MustText()
	// skip the next two
	case 1, 2:
		return nil
	// get the rarity index with helper function
	case 3:
		rarityIndex := helper.FindStringIndex(rarities, data.MustText())
		creature.rarity = strconv.Itoa(rarityIndex + 1)
	// find the rarity index with helper function
	case 4:
		sizeIndex := helper.FindStringIndex(sizes, data.MustText())
		creature.size = strconv.Itoa(sizeIndex + 1)
	// get all of the traits by adding them to a slice
	case 5:
		traitSlice := []string{}
		traits, err := data.Elements("a")
		if err != nil {
			return err
		}
		for _, trait := range traits {
			traitSlice = append(traitSlice, trait.MustText())
		}
		creature.traits = traitSlice
	// get the level and convert to integer
	case 6:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.level = string
	// extract the number from the hp
	case 7:
		string, err := helper.ExtractNumber(data.MustText())
		if err != nil {
			return err
		}
		creature.hp = string
	case 8:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.ac = string
	case 9:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.fortitude = string

	case 10:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.reflex = string
	case 11:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.will = string

	case 12:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.perception = string

	case 13:
		creature.senses = data.MustText()
	case 14:
		creature.speed = data.MustText()
	default:
		return fmt.Errorf("unexpected column index: %d", index)
	}
	return nil
}

func main() {
	// get the config form the .env
	cfg, _ := config.GetConfig()
	// create the string we use to connect to the database
	dbString := fmt.Sprintf("%v:%v@tcp(%v:%v)/%v", cfg.Username, cfg.Password, cfg.Host, cfg.Port, cfg.Database)
	// open the connection
	db, err := sql.Open("mysql", dbString)
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	// if nothing went wrong check if we are actually connected to the database
	err = db.Ping()
	if err != nil {
		log.Fatal(err)
	}
	// result, err := db.Exec(`INSERT INTO sizes (name) VALUES (?)`, "test")
	// if err != nil {
	// 	log.Fatal(err)
	// }
	// id, _ := result.LastInsertId()
	// fmt.Println(id)
	// fmt.Println(cfg)

	// Launch a new browser with default options, and connect to it.
	//if not installed this should install it
	browser := rod.New().MustConnect()
	defer browser.MustClose()
	//go to the page
	page := browseToPage(browser, "https://2e.aonprd.com/Creatures.aspx")
	// there will be a consent button that might block is which we need to click
	// it is always there so you can click it always even when not necessary
	consentButton := page.MustElement("button.fc-cta-consent")
	fmt.Println("button found")
	//wait until it is actually enabled and click it
	consentButton.MustWaitEnabled().MustClick()
	fmt.Println("button clicked")
	// find the nethys search element because this has the shadow we need
	div := page.MustElement("nethys-search")

	// get the shadow root here we do want an error
	shadow, err := div.ShadowRoot()
	if err != nil {
		log.Fatal(err)
	}
	//look for and click on the load remaining button
	button, err := shadow.ElementR("button", "Load remaining")
	if err != nil {
		log.Fatal(err)
	}
	fmt.Println(button.MustText())
	button.MustClick().MustWaitStable()
	fmt.Println("button clicked")
	fmt.Println("waiting")
	//since there is no way to know if the website is loaded we wait for 10 seconds before proceeding
	time.Sleep(10 * time.Second)
	// get all of the rows from the shadow
	rows, err := shadow.Elements("tr")
	if err != nil {
		log.Fatal(err)
	}
	var creatures []Creature

	// looop the rows
	for index, row := range rows {
		fmt.Printf("%d: adding...\n", index)

		if index == 0 {
			continue
		}
		// create a new creature object and populate it with the right values
		newCreature := Creature{}
		data, err := row.Elements("td")
		if err != nil {
			log.Fatal(err)
		}
		sizes := []string{"tiny", "small", "medium", "large", "huge", "gargantuan"}
		rarities := []string{"common", "uncommon", "rare", "unique"}
		for index, tableData := range data {
			err := parseTableData(index, &newCreature, tableData, sizes, rarities)
			if err != nil {
				log.Fatal(err)
			}
		}
		// add it to the slice
		creatures = append(creatures, newCreature)

	}
	// look for the lastID we can find in the creatures table
	lastID, err := helper.GetLastIdFromTable("creatures", db)
	if err != nil {
		log.Fatal(err)
	}
	// prepare the statements we will use to make sure this happens as fast as possible
	creatureStatement, err := db.Prepare("INSERT INTO creatures(name, size_id, level, hp, ac, fortitude, reflex, will, perception, senses, speed, rarity_id, custom) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,0)")
	if err != nil {
		log.Fatal(err)
	}
	defer creatureStatement.Close()
	traitStatement, err := db.Prepare("INSERT INTO pathfinder_traits (name) VALUES (?)")
	if err != nil {
		log.Fatal(err)
	}
	defer traitStatement.Close()
	creatureTraitStatement, err := db.Prepare("INSERT INTO creature_pathfinder_trait (creature_id, pathfinder_trait_id) VALUES (?,?)")
	if err != nil {
		log.Fatal(err)
	}
	defer creatureTraitStatement.Close()

	var traits []string
	fmt.Println(traits)
	// start adding everything to the database
	for index, creature := range creatures {
		fmt.Printf("adding #%v to database...\n", index)
		// execute the prepared statement to add a creature to the database
		_, err := creatureStatement.Exec(creature.name, creature.size, creature.level, creature.hp, creature.ac, creature.fortitude, creature.reflex, creature.will, creature.perception, creature.senses, creature.speed, creature.rarity)
		if err != nil {
			log.Fatal(err)
		}
		// loop throught the traits of the creature
		// are they not in our coverage array add them to the database otherwise get the index
		// can be done with a map might add later
		for _, element := range creature.traits {
			element = strings.ToLower(element)
			if !slices.Contains(traits, element) {
				traits = append(traits, element)
				_, err := traitStatement.Exec(element)
				if err != nil {
					log.Fatal(err)
				}
			}
			_, err := creatureTraitStatement.Exec(index+1+lastID, helper.FindStringIndex(traits, element)+1)
			if err != nil {
				log.Fatal(err)
			}
		}
		fmt.Printf("\n#%v added.......✔\n", index)

	}
	// creatures = slices.Delete(creatures, 0, 1)
	// fmt.Printf("%v", creatures)
	fmt.Println("done")
}
