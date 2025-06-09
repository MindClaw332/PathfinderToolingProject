package helper

import (
	"fmt"
	"strconv"
	"strings"
	"unicode"
)

func ExtractNumber(s string) (int, error) {
	trimmed := strings.TrimSpace(s)
	var numStr strings.Builder

	for _, rune := range trimmed {
		if unicode.IsDigit(rune) {
			numStr.WriteRune(rune)
		} else if numStr.Len() > 0 {
			break
		}
	}

	if numStr.Len() == 0 {
		return 0, fmt.Errorf("no number found in string: %q", s)
	}

	return strconv.Atoi(numStr.String())
}
