# Loan fee based calculator
This project provides a solution for calculating loan fees based on a given loan amount and term.   
The calculator is run through a single console script entrypoint `bin/calculate-fee` returning the following exit codes:
- **0** - Success 
- **1** - Custom Error for Handled Exceptions 
- **2** - Error for Not Handled Exceptions

## Project requirements (installed locally)
- PHP 8.4.x
- Composer 2.2.x

## Project Setup

### Install code dependencies
```sh
composer install
```

### Run the Project tests
```sh
composer test
```

### Run the command
```sh
./bin/calculate-fee
```

## Technical Approach
The main challenge is to accurately calculate a loan fee using a set of tiered breakpoints and to handle linear interpolation
and rounding rules as required:
- The fee structure does not follow a formula.
- Values in between the breakpoints should be interpolated linearly between the lower bound and upper bound that they fall between.
- The number of breakpoints, their values, or storage might change.
- The term can be either 12 or 24 (the number of months). You can also assume values will always be within this set.
- The fee should be rounded up such that the sum of the fee and the loan amount is exactly divisible by £5.
- The minimum amount for a loan is £1,000, and the maximum is £20,000.
- You can assume values will always be within this range but **there may be any values up to 2 decimal places**.

### PHP 8.4 Features
- Enums: The LoanTerm enum provides a safe, type-hinted way to represent valid loan terms (e.g., 12 or 24 months),
preventing invalid integer values from being used in calculations.
- Readonly Classes: The Monetary class is declared as readonly. This ensures its properties are immutable after a value object is created,
preventing accidental modification and making the code more predictable and bug-resistant.

### Object-Oriented Principles
- Encapsulation: Classes like Monetary and LoanFeeCalculator hide their internal logic. For example, the Monetary class handles the
conversion of currency to cents internally, so the rest of the application can work with precise values without worrying about floating-point errors.
- Interface Inheritance: The FeeStructureInterface defines a contract for classes that implement it. This allows the FeeStructureFactory to 
return different concrete fee structure classes (TwelveMonthFeeStructure, TwentyFourMonthFeeStructure)
- Immutability: The Monetary and LoanFeeCalculatorParams classes are immutable. Once created, their state cannot change,
which makes them thread-safe and much easier to reason about.
- Type Safety: Strict type hinting (declare(strict_types=1)) and type declarations are used throughout the codebase to ensure that functions
and methods receive the correct data types.

### SOLID Principles
- **S**ingle Responsibility Principle: Each class is designed to have a single, clear purpose. The Monetary class is only responsible for
handling currency values, while the LoanFeeCalculator is solely responsible for performing the fee calculation logic.
- **I**nterface Segregation Principle: The FeeStructureInterface is small and cohesive, preventing the fee structure classes
(TwelveMonthFeeStructure, TwentyFourMonthFeeStructure) from being forced to implement unnecessary methods.
- **D**ependency Inversion Principle: The LoanFeeCalculator depends on a LoanFeeCalculatorParams class, which is an abstraction of the input data,
rather than directly on raw, unvalidated floats or strings. This allows the calculator to be used with different data sources in the future.

### Design Patterns
- Factory Pattern: The FeeStructureFactory is a classic example of the Factory pattern. It encapsulates the logic for creating different
FeeStructure objects based on the requested loan term, abstracting the instantiation process and simplifying the LoanFeeCalculator class.

## Testing Strategy
The solution includes a comprehensive test suite to ensure its reliability, divided into two main categories:

### Unit Tests
- MonetaryTest: tests the Monetary value object in isolation. It verifies that values are correctly handled, that floating-point numbers
are rounded and stored precisely as integers (cents) and that the formatting methods work as expected.
- FeeStructureFactoryTest: ensures the factory correctly returns instances of the FeeStructureInterface and throws an exception for
invalid term inputs.

### Feature/End-to-End Tests
- LoanFeeCalculatorTest: test the entire fee calculation process from end-to-end. It uses a data provider to test a variety of scenarios,
including exact breakpoints, linear interpolation and rounding to ensure the core business logic is met.

## Domain Driven Design (DDD) approach
In order to structure and organize the domain model for the loan fee calculator, the project files are organized
under the src/**FeeCalculator** aggregate folder in 3 context layers:
- **Application**
  - Exception (Handled application exceptions)
    - LoanTermBreakpointException, LoanTermException
  - Factory (Create instances of complex domain objects like DTO)
    - FeeStructureFactory
  - Service (Application behavior or operation that does not naturally belong to any specific domain object)
    - LoanFeeCalculator
- **Domain**
  - DTO (Data Transfer Object used as domain configuration)
    - TwelveMonthFeeStructure, TwentyFourMonthFeeStructure
  - Enum (Represent domain model enum values)
    - LoanTerm, LoanTermBreakpoint
  - Service (Perform specific domain tasks or enforcing domain rules)
    - FeeInterpolator
  - ValueObject (Type of domain object that represents a value that is conceptually unchangeable)
    - LoanFeeCalculatorParams, Monetary
- **UI** (User Interaction)
  - CLI (The calculator is run through a single console command)
    - LoanFeeCalculatorCommand
