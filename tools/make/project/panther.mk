PHONY += test-panther
test-panther: ## Run Panther tests with PHPUnit
	@echo "- ${YELLOW}test-panther:${NO_COLOR} Start running PHPUnit tests..."
ifeq (${PHPUNIT_BIN_EXISTS},yes)
    # Unfortunately Panther cannot be ran inside same container, so
    # forcing it to run on host
	$(call composer_on_host,test-panther)
else
	@echo "- ${YELLOW}${PHPUNIT_BIN} does not exist! ${RED}[ERROR]${NO_COLOR}"
endif
