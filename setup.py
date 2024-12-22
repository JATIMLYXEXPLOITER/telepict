import os

def setup():
    print("In order for this script to run properly, first setup the .env config file, let's fill in your bot token and user ID in the .env setup file.\n")
    token = input("token bot: ")
    uid = input("user id: ")
    fl = ".env"
    if not os.path.exists(fl):
        with open(fl, "w") as b:
            pass
    with open(fl, "w") as w:
        w.write(f"BOT_TOKEN={token}\n")
        w.write(f"USER_ID={uid}")
    print("file .env has been created, do you want to test this script?")
    test = input("yes/no ")
    if test == "yes":
        os.system("php -S localhost:3000")
    elif test == "no":
        print("happy using it!")
    else:
        print("wrong bro")

if __name__ == "__main__":
    setup()