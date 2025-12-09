import time 

mainState = True
sideState = False
pedestrianState = False
run = 1
def main(run):
    print(f"Run {run}")
    carWaiting = getCarWaiting()
    pedestrianWaiting = getPedestrianWaiting()
    if carWaiting or pedestrianWaiting:
        runCycle(carWaiting, pedestrianWaiting)
    else:
        print(f"Main: {mainState}, Side: {sideState}, Pedestrian: {pedestrianState}")
    run += 1
    print("\n")
    main(run)
    
def getCarWaiting():
    wating = input("Is there a car waiting on the side road? (Y/N) >>").upper()
    if wating == "Y":
        return True
    else:
        return False

def getPedestrianWaiting():
    wating = input("Is there a car pedestrian waiting? (Y/N) >>").upper()
    if wating == "Y":
        return True
    else:
        return False
    
def runCycle(carWaiting, pedestrianWaiting):
    if carWaiting:
        mainState = False
        sideState = True
        pedestrianState = False
        print(f"Main: {mainState}, Side: {sideState}, Pedestrian: {pedestrianState}")
        time.sleep(2)
        pedestrianState = False
        sideState = False
        mainState = True
    if pedestrianWaiting:
        mainState = False
        sideState = False
        pedestrianState = True
        print(f"Main: {mainState}, Side: {sideState}, Pedestrian: {pedestrianState}")
        time.sleep(2)
        pedestrianState = False
        sideState = False
        mainState = True
        print(f"Main: {mainState}, Side: {sideState}, Pedestrian: {pedestrianState}")

main(run)