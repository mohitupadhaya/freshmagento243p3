import subprocess

print("Checking staged files....")

result = subprocess.run(
	["git", "diff", "--cached",
	 "--name-only"],
	 capture_output=True,
	 text=True

	)
files = result.stdout.splitlines()

php_files = [

    f for f in files
    if f.endswith(".php") or 

f.endswith(".phtml")    

]

print("Changed files:")

for f in php_files:
	print(f)