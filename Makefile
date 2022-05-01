test:
	@docker build -t everest-test . -q > /dev/null
	@docker run --rm --name everest-test-tmp-container everest-test